(function($) {

	var PE = PixasticEditor;

	function makeSlider(label, id, min, max, step, defaultVal, onChange) {
		var $ctr = $("<div></div>", PE.getDocument())
			.addClass("ui-slider-container");

		var $label = $("<label></label>", PE.getDocument())
			.addClass("ui-slider-label")
			.attr("for", "input-slider-" + id)
			.html(label + ":")
			.appendTo($ctr);

		var $value = $("<div></div>", PE.getDocument())
			.addClass("ui-slider-value")
			.html(defaultVal());

		var $valueField = $("<input type='hidden'>", PE.getDocument())
			.attr("id", "input-hidden-" + id)
			.val(defaultVal())
			.appendTo($ctr);

		var performOnChange = true;

		var $slider = $("<div class='ui-slider'><div class='ui-slider-handle'></div><div class='ui-slider-range'></div></div>", PE.getDocument())
			.appendTo($ctr)
			.attr("id", "input-slider-" + id)
			.slider({
				slide: function() {
					$value.html($(this).slider("value"));
					$valueField.val($(this).slider("value"));
				},
				change : function() {
					$value.html($(this).slider("value"));
					$valueField.val($(this).slider("value"));
					if (onChange && performOnChange)
						onChange();
				},
				min : min,
				max : max,
				step : step,
				value : defaultVal()
			});

		$value.appendTo($ctr);

		return {
			container : $ctr,
			label : $label,
			slider : $slider,
			valueText : $value,
			valueField : $valueField,
			reset : function() {
				performOnChange = false;
				$value.html(defaultVal());
				$valueField.val(defaultVal());
				$slider.slider("value", defaultVal());
				performOnChange = true;
			}
		};
	}

	function makeCheckbox(label, id, defaultVal, onChange) {
		var $ctr = $("<div></div>", PE.getDocument())
			.addClass("ui-checkbox-container");

		var $label = $("<label></label>", PE.getDocument())
			.addClass("ui-checkbox-label")
			.attr("for", "input-checkbox-" + id)
			.html(label + ":")
			.appendTo($ctr);

		var $valueField = $("<input type='hidden'>", PE.getDocument())
			.attr("id", "input-hidden-" + id)
			.val(defaultVal())
			.appendTo($ctr);

		var performOnChange = true;

		var $checkbox = $("<input type=\"checkbox\"></input>", PE.getDocument())
			.addClass("ui-checkbox")
			.attr("id", "input-checkbox-" + id)
			.attr("checked", defaultVal())
			.appendTo($ctr)
			.change(function() {
				$valueField.val(this.checked);
				if (onChange && performOnChange)
					onChange();
			});

		return {
			container : $ctr,
			label : $label,
			checkbox : $checkbox,
			valueField : $valueField,
			reset : function() {
				performOnChange = false;
				$checkbox.attr("checked", defaultVal());
				$valueField.val(defaultVal());
				performOnChange = true;
			}
		};
	}

	function makeSelect(label, id, values, defaultVal, onChange) {
		var $ctr = $("<div></div>", PE.getDocument())
			.addClass("ui-select-container");

		var $label = $("<label></label>", PE.getDocument())
			.addClass("ui-checkbox-label")
			.attr("for", "input-checkbox-" + id)
			.html(label + ":")
			.appendTo($ctr);

		var $valueField = $("<input type='hidden'>", PE.getDocument())
			.attr("id", "input-hidden-" + id)
			.val(defaultVal())
			.appendTo($ctr);

		var selectHtml = "<select>";
		for (var i=0;i<values.length;i++) {
			selectHtml += "<option value='" + values[i].value + "' " 
				+ (defaultVal() == values[i].value ? "selected" : "") 
				+ ">" + values[i].name + "</option>";
		}
		selectHtml += "</select>";

		var $select = $(selectHtml).appendTo($ctr);

		var performOnChange = true;

		$select.change(
			function() {
				$valueField.val(this.options[this.selectedIndex].value);
				if (onChange && performOnChange)
					onChange();
			}
		);

		return {
			container : $ctr,
			label : $label,
			select : $select,
			valueField : $valueField,
			reset : function() {
				performOnChange = false;
				var defVal = defaultVal();
				$select.val(defVal);
				$valueField.val(defVal);
				performOnChange = true;
			}
		};
	}

	function makeNumericInput(label, labelRight, id, min, max, step, defaultVal, onChange) {
		var $ctr = $("<div></div>", PE.getDocument())
			.addClass("ui-textinput-container");

		var $label = $("<label></label>", PE.getDocument())
			.addClass("ui-textinput-label")
			.attr("for", "input-numeric-" + id)
			.html(label + ":")
			.appendTo($ctr);

		var $valueField = $("<input type='hidden'>", PE.getDocument())
			.attr("id", "input-hidden-" + id)
			.val(defaultVal())
			.appendTo($ctr);

		var performOnChange = true;

		function setVal(val) {
			val = Math.min(max, val);
			val = Math.max(min, val);
			$textInput.val(val);
			$valueField.val(val);
		}

		var $textInput = $("<input type=\"text\"></input>", PE.getDocument())
			.addClass("ui-textinput")
			.addClass("ui-numericinput")
			.appendTo($ctr)
			.val(defaultVal())
			.attr("id", "input-numeric-" + id)
			.change(function() {
				var val = parseFloat(this.value);
				setVal(val);
				if (onChange && performOnChange)
					onChange();
			})
			.keydown(function(e) {
				var val = parseFloat($(this).val());
				if (e.keyCode == 38) { // up
					setVal(val + step);
				}
				if (e.keyCode == 40) { // down
					setVal(val - step);
				}
			});

		if (labelRight) {
			var $labelRight = $("<label></label>", PE.getDocument())
				.addClass("ui-textinput-label-right")
				.html(labelRight)
				.appendTo($ctr);
		}

		return {
			container : $ctr,
			label : $label,
			textinput : $textInput,
			valueField : $valueField,
			reset : function() {
				performOnChange = false;
				setVal(defaultVal());
				performOnChange = true;
			}
		};
	}

	function makeButton(text) {
		var $button = $("<button></button>", PE.getDocument()).html(text);
		return $button;
	}


	PE.UI = {
		makeSlider : makeSlider,
		makeCheckbox : makeCheckbox,
		makeNumericInput : makeNumericInput,
		makeSelect : makeSelect,
		makeButton : makeButton
	}

})(PixasticEditor.jQuery);

