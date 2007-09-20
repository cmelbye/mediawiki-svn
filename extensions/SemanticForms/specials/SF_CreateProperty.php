<?php
/**
 * A special page holding a form that allows the user to create a semantic
 * property (an attribute or relation).
 *
 * @author Yaron Koren
 */

include_once $sfgIP . "/includes/SF_TemplateField.inc";

if (!defined('MEDIAWIKI')) die();

global $IP;
require_once( "$IP/includes/SpecialPage.php" );

SpecialPage::addPage( new SpecialPage('CreateProperty','',true,'doSpecialCreateProperty',false) );

function createPropertyText($property_type, $allowed_values_str) {
  global $smwgContLang;

  $namespace_labels = $smwgContLang->getNamespaceArray();
  if ($property_type == $namespace_labels[SMW_NS_RELATION]) {
    $text = wfMsg('sf_createproperty_isrelation');
  } else {
    global $smwgContLang;
    $specprops = $smwgContLang->getSpecialPropertiesArray();
    $type_tag = "[[" . $specprops[SMW_SP_HAS_TYPE] . "::" .
      $namespace_labels[SMW_NS_TYPE] . ":$property_type|$property_type]]";
    $text = wfMsg('sf_createproperty_isattribute', $type_tag);
    if ($property_type == $smwgContLang->getDatatypeLabel('smw_enum')) {
      $text .= "\n\n" . wfMsg('sf_createproperty_allowedvals');
      // replace the comma substitution character that has no chance of
      // being included in the values list - namely, the ASCII beep
      $allowed_values_str = str_replace('\,', "\a", $allowed_values_str);
      $allowed_values_array = explode(',', $allowed_values_str);
      foreach ($allowed_values_array as $i => $value) {
        // replace beep with comma, trim
        $value = str_replace("\a", ',', trim($value));
        $text .= "\n* [[" . $specprops[SMW_SP_POSSIBLE_VALUE] . ":=$value]]";
      }
    }
  }
  return $text;
}

function doSpecialCreateProperty() {
  global $wgOut, $wgRequest, $sfgScriptPath;
  global $smwgContLang;

  # cycle through the query values, setting the appropriate local variables
  $property_name = $wgRequest->getVal('property_name');
  $property_type = $wgRequest->getVal('property_type');
  $allowed_values = $wgRequest->getVal('values');

  $preview_button_text = wfMsg('preview');
  if ($wgRequest->getVal('preview') == $preview_button_text) {
    # validate property name
    if ($property_name == '') {
      $property_name_error_str = wfMsg('sf_blank_error');
    } else {
      # redirect to wiki interface
      $namespace_labels = $smwgContLang->getNamespaceArray();
      $namespace = ($property_type == $namespace_labels[SMW_NS_RELATION]) ? SMW_NS_RELATION : SMW_NS_ATTRIBUTE;
      $title = Title::newFromText($property_name, $namespace);
      $submit_url = $title->getLocalURL('action=submit');
      $full_text = createPropertyText($property_type, $allowed_values);
      // HTML-encode
      $full_text = str_replace('"', '&quot;', $full_text);
      $text .= <<<END
  <form id="editform" name="editform" method="post" action="$submit_url">
    <input type="hidden" name="wpTextbox1" id="wpTextbox1" value="$full_text" />
  </form>
      <script>
      document.editform.submit();
      </script>

END;
      $wgOut->addHTML($text);
      return;
    }
  }
  $all_properties = getSemanticProperties();
  $enum_str = $smwgContLang->getDatatypeLabel('smw_enum');

  $javascript_text =<<<END
function toggleAllowedValues() {
	var values_div = document.getElementById("allowed_values");
        var prop_dropdown = document.getElementById("property_dropdown");
        if (prop_dropdown.value == "$enum_str") {
		values_div.style.display = "block";
	} else {
		values_div.style.display = "none";
        }
}

END;

  $namespace_labels = $smwgContLang->getNamespaceArray();
  $datatype_labels = array(
    $namespace_labels[SMW_NS_RELATION],
    $smwgContLang->getDatatypeLabel('smw_string'),
    $smwgContLang->getDatatypeLabel('smw_int'),
    $smwgContLang->getDatatypeLabel('smw_float'),
    $smwgContLang->getDatatypeLabel('smw_datetime'),
    $smwgContLang->getDatatypeLabel('smw_bool'),
    $smwgContLang->getDatatypeLabel('smw_enum'),
    $smwgContLang->getDatatypeLabel('smw_url'),
    $smwgContLang->getDatatypeLabel('smw_uri'),
    $smwgContLang->getDatatypeLabel('smw_email'),
    $smwgContLang->getDatatypeLabel('smw_temperature'),
    $smwgContLang->getDatatypeLabel('smw_geocoordinate')
  );

  // set 'title' as hidden field, in case there's no URL niceness
  global $wgContLang;
  $mw_namespace_labels = $wgContLang->getNamespaces();
  $special_namespace = $mw_namespace_labels[NS_SPECIAL];
  $text .=<<<END
	<form action="" method="get">
	<input type="hidden" name="title" value="$special_namespace:CreateProperty">
	<p>Name: <input size="25" name="property_name" value="">
	<span style="color: red;">$property_name_error_str</span>
	Type:
	<select id="property_dropdown" name="property_type" onChange="toggleAllowedValues();">
END;
  foreach ($datatype_labels as $label) {
    $text .= "	<option>$label</option>\n";
  }

  $text .=<<<END
	</select>
	<div id="allowed_values" style="display: none; margin-bottom: 15px;">
	<p>Enter list of allowed values, separated by commas (if a value contains a comma, replace it with "\,"):</p>
	<p><input size="35" name="values" value=""></p>
	</div>
	<p><input type="submit" name="preview" value="$preview_button_text"></p>

END;

  $text .= "	</form>\n";

  $wgOut->addLink( array(
    'rel' => 'stylesheet',
    'type' => 'text/css',
    'media' => "screen, projection",
    'href' => $sfgScriptPath . "/skins/SF_main.css"
  ));
  $wgOut->addScript('<script type="text/javascript">' . $javascript_text . '</script>');
  $wgOut->addHTML($text);
}
