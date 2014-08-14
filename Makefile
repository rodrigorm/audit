builddir := build
srcdir := src
testsdir := tests
logsdir := $(builddir)/logs
pdependdir := $(builddir)/pdepend

SRC := $(shell find "$(srcdir)" -type f -name "*.php")
TESTS := $(shell find $(testsdir) -type f -name "*.php")

BIN := vendor/bin
PHPLOC := $(BIN)/phploc
PDEPEND := $(BIN)/pdepend
PHPMD := $(BIN)/phpmd
PHPCS := $(BIN)/phpcs
PHPCPD := $(BIN)/phpcpd
PHPUNIT := $(BIN)/phpunit

.PHONY : build clean

build : lint phploc pdepend phpmd-ci phpcs-ci phpcpd phpunit

# Cleanup build artifacts
clean :
	rm -rf "$(builddir)/coverage"
	rm -rf "$(logsdir)"
	rm -rf "$(pdependdir)"

# Perform syntax check of sourcecode files
lint : $(logsdir)/lint.log
$(logsdir)/lint.log : $(logsdir) $(SRC) $(TESTS)
	echo $(SRC) $(TESTS) | xargs -n 1 php -l | tee $@

# Measure project size using PHPLOC
phploc : $(logsdir)/phploc.csv
$(logsdir)/phploc.csv : $(PHPLOC) $(logsdir) $(SRC)
	$(PHPLOC) --log-csv "$(logsdir)/phploc.csv" "$(srcdir)"

# Calculate software metrics using PHP_Depend
pdepend : $(logsdir)/jdepend.xml
$(logsdir)/jdepend.xml : $(PDEPEND) $(logsdir) $(pdependdir) $(SRC)
	$(PDEPEND) --jdepend-xml="$(logsdir)/jdepend.xml" --jdepend-chart="$(builddir)/pdepend/dependencies.svg" --overview-pyramid="$(builddir)/pdepend/overview-pyramid.svg" "$(srcdir)"

# Perform project mess detection using PHPMD and print human readable output. Intended for usage on the command line before committing.
phpmd : $(logsdir)/pmd.log
$(logsdir)/pmd.log : $(PHPMD) $(logsdir) $(SRC)
	$(PHPMD) "$(srcdir)" text "$(builddir)/phpmd.xml" | tee $@

# Perform project mess detection using PHPMD creating a log file for the continuous integration server
phpmd-ci : $(logsdir)/pmd.xml
$(logsdir)/pmd.xml : $(PHPMD) $(logsdir) $(SRC) $(builddir)/phpmd.xml
	$(PHPMD) "$(srcdir)" xml "$(builddir)/phpmd.xml" --reportfile "$(logsdir)/pmd.xml"; true

# Find coding standard violations using PHP_CodeSniffer and print human readable output. Intended for usage on the command line before committing.
phpcs : $(logsdir)/checkstyle.log
$(logsdir)/checkstyle.log : $(PHPCS) $(logsdir) $(builddir)/phpcs.xml $(SRC)
	$(PHPCS) --standard="$(builddir)/phpcs.xml" "$(srcdir)" | tee $@

# Find coding standard violations using PHP_CodeSniffer creating a log file for the continuous integration server
phpcs-ci : $(logsdir)/checkstyle.xml
$(logsdir)/checkstyle.xml : $(PHPCS) $(logsdir) $(SRC) $(builddir)/phpcs.xml
	@$(PHPCS) --report=checkstyle --report-file="$(logsdir)/checkstyle.xml" --standard="$(builddir)/phpcs.xml" "$(srcdir)"; true

# Find duplicate code using PHPCPD
phpcpd : $(logsdir)/pmd-cpd.xml
$(logsdir)/pmd-cpd.xml : $(PHPCPD) $(logsdir) $(SRC)
	$(PHPCPD) --log-pmd "$(logsdir)/pmd-cpd.xml" "$(srcdir)"

# Run unit tests with PHPUnit
phpunit : $(logsdir)/junit.xml
$(logsdir)/junit.xml : $(PHPUNIT) $(logsdir) $(SRC) $(TESTS)
	$(PHPUNIT)

trace : $(logsdir)/trace.xt
$(logsdir)/trace.xt : $(PHPUNIT) $(logsdir) $(SRC) $(TESTS)
	php \
		-dmemory_limit=-1 \
		-dxdebug.auto_trace=1 \
		-dxdebug.trace_output_dir=$(realpath $(dir $@))\
		-dxdebug.trace_output_name=$(basename $(notdir $@))\
		-dxdebug.trace_format=1 \
		-dxdebug.collect_assignments=0 \
		-dxdebug.collect_includes=1 \
		-dxdebug.collect_params=1 \
		-dxdebug.collect_return=0 \
		`which $(PHPUNIT)`

$(PHPLOC) $(PDEPEND) $(PHPMD) $(PHPCS) $(PHPCPD) $(PHPUNIT) : composer.lock
composer.lock : composer.json
	composer install && \
		composer update && \
		touch $@ # Force update when there is nothing to install

$(logsdir) : $(logsdir)/.done
$(pdependdir) : $(pdependdir)/.done
%/.done :
	@mkdir -p $(dir $@)
	@touch $@
