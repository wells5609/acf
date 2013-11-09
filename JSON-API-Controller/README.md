JSON-API-ACF-Controller
=======================

A WordPress JSON API controller to replicate Advanced Custom Fields functions.

## Methods

Currently available methods:
* `get_field_objects`
* `get_fields`


## Usage

Depending on your JSON API plugin setup, the controller methods are available via:
  `http://yoursite.com/api/acf/{{method_name}}/?post_id={{post_id}}&fields={{fieldnames}}`


### Examples

Example 1: Get a field value for a post:
  `http://yoursite.com/api/acf/get_fields/?post_id=123&fields=my_custom_field`

Example 2: get all field objects for a post:
  `http://yoursite.com/api/acf/get_field_objects/?post_id=123&fields=*`
  `http://yoursite.com/api/acf/get_field_objects/?post_id=123&fields=all`
  `http://yoursite.com/api/acf/get_field_objects/?post_id=123`


## Notes
* As shown above, for the `get_fields` and `get_field_objects` methods, specifying `fields=*`, `fields=all`, or simply omitting `fields` will get all field values/objects associated with the post. 
* Post ID is required.
