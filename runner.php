<?php 
/**
 * SKU Updater (runner)
 * @package SkuUpdater
 */

require_once 'autoload.php';

/**
 * Configuration of all options.
 * - configure all of the column options.
 * - configure all of the head names.
 * - configure all of the colloquial.
 */
$options = [
	// all column head options
	"status_head" => "",
	"old_sku_head" => "",
	"new_sku_head" => "",

	// all of the colloquial options
	"status_head_choice_update" => "",
	"status_head_choice_delete" => "",
];

/**
 * New up a new instance of the SkuUpdater. 
 */
$updater = new SkuUpdater("filename.csv",$options);

/**
 * Execute the updateSKUs method.
 */
$updater->updateSKUs();