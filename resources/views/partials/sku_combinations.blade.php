@if(count($combinations[0]) > 0)
	<table class="table table-bordered table-responsive">
		<thead>
			<tr>
				<td class="text-center">
					<label for="" class="control-label">#</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{translate('Variant')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{translate('Image')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{translate('Variant Price')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{translate('Variant Price Usd')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{translate('SKU')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{translate('Quantity')}}</label>
				</td>
			</tr>
		</thead>
		<tbody>

	<?php $index = 1; ?>
@foreach ($combinations as $key => $combination)
	@php
		$sku = '';
		foreach (explode(' ', $product_name) as $key => $value) {
			$sku .= substr($value, 0, 1);
		}

		$str = '';
		foreach ($combination as $key => $item){
			if($key > 0 ){
				$str .= '-'.str_replace(' ', '', $item);
				$sku .='-'.str_replace(' ', '', $item);
			}
			else{
				if($colors_active == 1){
					$color_name = \App\Color::where('code', $item)->first()->name;
					$str .= $color_name;
					$sku .='-'.$color_name;
				}
				else{
					$str .= str_replace(' ', '', $item);
					$sku .='-'.str_replace(' ', '', $item);
				}
			}
		}
	@endphp
	@if(strlen($str) > 0)
			<tr>
				<td>{{$index}}</td>
				<?php $index++; ?>
				<td>
					<label for="" class="control-label">{{ $str }}</label>
				</td>
				<td>
					<div id="variant-images">
						<div class="col-md-10">
							<div class="custom-file">
								<input type="file" name="{{ $str }}[]" id="photo{{ $sku }}-1" class="custome-file-input" multiple data-multiple-caption="{count} files selected" accept="image/*" required />
								<input type="hidden" name="selected_color[]" value="{{ $str }}">
								<label for="photo{{ $sku }}-1" class="mw-100 mb-3"></label>
							</div>
						</div>
					</div>
					<div>
						<img id="aadhar_pre">
					</div>
				</td>
				<td>
					<input type="number" name="price_{{ $str }}" value="{{ $unit_price }}" min="0" step="0.01" class="form-control" required>
				</td>
				<td>
					<input type="number" name="price_{{ $str }}_usd" value="{{ $price_usd }}" min="0" step="0.01" class="form-control" required>
				</td>
				<td>
					<input type="text" name="sku_{{ $str }}" value="{{ $sku }}" class="form-control" required>
				</td>
				<td>
					<input type="number" name="qty_{{ $str }}" value="10" min="0" step="1" class="form-control" required>
				</td>
			</tr>
	@endif
@endforeach
	</tbody>
</table>
@endif
