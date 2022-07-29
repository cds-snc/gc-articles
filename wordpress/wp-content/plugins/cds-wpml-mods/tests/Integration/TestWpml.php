<?php

use CDS\Wpml\Wpml;

test('getInstance', function() {
	$plugin = Wpml::getInstance();
	$this->assertInstanceOf(Wpml::class, $plugin);
});

test('Plugin is installed', function() {
	$this->assertTrue(defined('CDS_WPML_PLUGIN_FILE_PATH'));
	$this->assertTrue(defined('CDS_WPML_PLUGIN_BASE_PATH'));
});

test('Setup', function() {
	$plugin = Wpml::getInstance();
	$plugin->setup();

	$this->assertInstanceOf(Wpml::class, $plugin);

});
