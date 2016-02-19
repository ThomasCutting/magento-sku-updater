# Magento SKU Updater.

This package includes a `BatchCSVProcessor`, and a `SkuUpdater` class.  The objective of the classes are to

 2. Load and process CSV files into an array format ( multi-dimensional & fully combined ) using the `BatchCSVProcessor` class.
 3. Import that above combined array into the `SkuUpdater` and update SKUs dynamically through a simple executable.

## Usage

Add options to the executable.  These options handle column names, and colloquial commands such as ( _"Delete This Row"_, or _"Update This SKU"_.
```php
$options = [
	// all column name options
	"status_head" => "",
	"old_sku_head" => "",
	"new_sku_head" => "",

	// all of the colloquial options
	"status_head_choice_update" => "",
	"status_head_choice_delete" => "",
];
```

Go ahead and create a new instance of the importer class.
```php
$updater = new SkuUpdater($file_name, $options);
```

Be sure that you have your `executable` directory writable by self, and group.  Then to _execute_ or _run_ the update process...

```php
$updater->updateSKUs();
```

The `above code` should be written within a blank PHP file.  To make sure you have access to the aforementioned classes, use the line below.
```php
require_once '/path/to/importer/autoload.php';
```