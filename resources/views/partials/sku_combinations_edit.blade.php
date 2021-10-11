@if(count($combinations[0]) > 0)
	<table class="table table-bordered">
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
				$str .= str_replace(' ', '', $item);
				$sku .= str_replace(' ', '', $item);
				$str = str_replace(',','', $str);
				$str = str_split($str);
				sort($str);
				$str = implode('',$str);
			}
			else{
				if($colors_active == 1){
					$color_name = \App\Color::where('code', $item)->first()->name;
					$str .= $color_name;
					$sku .= $color_name;
					$str = str_replace(',','', $str);
					$str = str_split($str);
					sort($str);
					$str = implode('',$str);
				}
				else{
					$str .= str_replace(' ', '', $item);
					$sku .= str_replace(' ', '', $item);
					$str = str_replace(',','', $str);
					$str = str_split($str);
					sort($str);
					$str = implode('',$str);
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
			<input type="hidden" name="qty_hide" id="qty_hide" value="{{ $qty_hide }}">
			<td>
			@foreach($product_stocks as $product_stock)
				@if (json_decode(isset($product_stock->variant_img)) != null)
					@if($product_stock->variant == $str)
						@foreach (json_decode($product_stock->variant_img) as $key => $photos)
							<div class="col-md-3">
								<div style="width: 100px; height: 100px;" class="img-upload-preview">
									<img loading="lazy"  style="width: 200px; height: 50px;" src="{{ my_asset($photos) }}" alt="" class="img-responsive">
									<input type="hidden" name="variant_img[]" value="{{ $photos }}">
									<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
								</div>
							</div>
						@endforeach
					@endif	
				@else
				@endif
			@endforeach
				<div id="variant-images">
					<div class="col-md-10">
						<input type="file" name="{{ $str }}[]" id="photo{{ $sku }}-1" class="custome-file-input" multiple data-multiple-caption="{count} files selected" accept="image/*" />
						<input type="hidden" name="selected_color[]" value="{{ $str }}">
						<label for="photo{{ $sku }}-1" class="mw-100 mb-3"></label>
					</div>
				</div>
				<!-- <button type="button" class="btn btn-info classAdd mb-3 right">{{  translate('Add Images') }}</button> -->
				<br><br>
			</td>

			@if($empty_product_stocks == 0)

			@elseif($empty_product_stocks == 1 )
			@foreach($product_stocks as $product_stock)
				@if($product_stock->variant == $str)
				
					<?php 
					$repl = ['Rs', '$'];
					$product_stock_price = str_replace($repl, '', single_price($product_stock->price)); ?>
					$product_stock_price_usd = str_replace($repl, '', single_price($product_stock->price_usd)); 
					<td>
						<input type="number" name="price_{{ $str }}" value="{{ $product_stock_price }}" class="form-control" required>
					</td>
					<td>
						<input type="number" name="price_{{ $str }}_usd" value="{{ $product_stock_price_usd }}" class="form-control" required>
					</td>
				@endif
			@endforeach
			@endif

			@if(!in_array($str, $variants_array))
				<td>
					<input type="number" name="price_{{ $str }}" value="" class="form-control" required>
				</td>
				<td>
					<input type="number" name="price_{{ $str }}_usd" value="" class="form-control" required>
				</td>
			@endif

			<td>
				<input type="text" name="sku_{{ $str }}" value="{{ $sku }}" class="form-control" required>
			</td>

			@if($empty_product_stocks == 0)
			
			@else
			@foreach($product_stocks as $product_stock)
				@if($product_stock->variant == $str)
					<td>
						<input type="number" name="qty_{{ $str }}" value="{{ $product_stock->qty }}"  class="form-control" required>
					</td>
				@endif
			@endforeach
			@endif
			
			@if(!in_array($str, $variants_array))
				<td>
					<input type="number" name="qty_{{ $str }}" value=""  class="form-control" required>
				</td>
			@endif
		</tr>
	@endif
@endforeach

	</tbody>
</table>
@endif



<script>
 $(document).ready(function(){
            $('.remove-files').on('click', function(){
                $(this).parents(".col-md-3	").remove();
            });
        });
</script>