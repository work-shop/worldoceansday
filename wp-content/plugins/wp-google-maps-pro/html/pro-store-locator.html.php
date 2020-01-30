<div class="wpgmza-store-locator wpgmza_sl_main_div">
	<div class="wpgmza-form-field wpgmza_sl_query_div wpgmza-address">
		<label 
			data-name="addressLabel"
			for="addressInput_"
			class="wpgmza-address-label wpgmza-form-field__label wpgmza-form-field__label--float wpgmza_sl_query_innerdiv1 wpgmza-address">
		</label>
		<input
			data-name="defaultAddress"
			type="text"
			id="addressInput_"
			class="wpgmza-form-field__input addressInput wpgmza-address"
			/>
	</div>
	
	<div class="wpgmza-form-field wpgmza_sl_query_div wpgmza-keywords">
		<label 
			data-name="keywordsLabel"
			for="nameInput_"
			class="wpgmza-form-field__label wpgmza-form-field__label--float wpgmza_sl_query_innerdiv1 wpgmza_name_search_string wpgmza-keywords">
		</label>
		<input
			type="text"
			class="wpgmza-text-search wpgmza-form-field__input wpgmza-keywords"
			id="nameInput_"
			/>
	</div>
	
	<div class="wpgmza-form-field wpgmza_sl_radius_div wpgmza-search-area">
		<label 
			for="radiusSelect_"
			class="wpgmza-form-field__label wpgmza-form-field__label--float wpgmza-search-area">
			<?php
			_e("Radius", "wp-google-maps");
			?>
		</label>
		<select class="wpgmza-form-field__input wpgmza_sl_radius_select wpgmza-search-area" id="radiusSelect_"></select>
	</div>
</div>