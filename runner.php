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
	"status_head" => "Status",
	"old_sku_head" => "Existing Web SKU",
	"new_sku_head" => "SKU we want listed",
	"upc_head" => "UPC",

	// all of the colloquial options
	"status_head_choice_update" => "Changed",
	"status_head_choice_delete" => "DELETE THIS SKU",
];

/**
 * New up a new instance of the SkuUpdater. 
 */
$updater = new SkuUpdater("skusToUpdate.csv",$options);

/**
 * Execute the updateSKUs method.
 */
$updater->updateSKUs();