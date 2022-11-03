<!DOCTYPE html>
<html dir="rtl" xmlns="http://www.w3.org/1999/xhtml 22" xml:lang="ar" lang="ar">


<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css"/>
    <meta charset="UTF-8">
	<style media="all">
		@font-face {
            font-family: 'Roboto';
            src: url("{{ my_asset('fonts/Roboto-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        *{
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: 'Roboto';
            color: #333542;
        }
        p {
		   font-family: 'DroidArabicKufiRegular';
		   font-weight: normal;
		   font-style: normal;
		}
		body{
			font-size: .875rem;
		}
		.gry-color *,
		.gry-color{
			color:#878f9c;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .5rem .7rem;
		}
		table.padding td{
			padding: .7rem;
		}
		table.sm-padding td{
			padding: .2rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:left;
		}
		.text-right{
			text-align:right;
		}
		.small{
			font-size: .85rem;
		}
		.currency{

		}
	</style>
</head>
<body>
	<div>

		@php
			$generalsetting = \App\GeneralSetting::first();
		@endphp

		<div style="background: #eceff4;padding: 1.5rem;">
			<table>
				<tr>
					<td>
						@if($generalsetting->{'logo_'.locale()} != null)
                                    <img src="{{ uploaded_asset($generalsetting->{'logo_'.locale()}) }}" alt="{{ env('APP_NAME') }}" style="width:150px;">
                                @else
                                    <img src="{{ my_asset('frontend/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}" style="width:150px;">
                                @endif
					</td>
					<td style="font-size: 2.5rem;" class="text-right strong">{{  translate('INVOICE') }}</td>
				</tr>
			</table>
			<table>
				<tr>
					<td class="strong">{{ $name }}</td>
					<td style="font-size: 1.2rem;" class="text-right strong"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ $address }}</td>
					<td class="text-right gry-color small"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{  translate('Email') }}: {{ $generalsetting->email }}</td>
					<td class="text-right small"><span class="gry-color small">{{  translate('Order ID') }}:</span> <span class="strong">{{ $order->code }}</span></td>
				</tr>
				<tr>
					<td class="gry-color small">{{  translate('Phone') }}: {{ $generalsetting->phone }}</td>
					<td class="text-right small"><span class="gry-color small">{{  translate('Order Date') }}:</span> <span class=" strong">{{ date('d-m-Y', $order->date) }}</span></td>
				</tr>
			</table>

		</div>

		<div style="padding: 1.5rem;padding-bottom: 0">
            <table>
				@php
					$shipping_address = json_decode($order->shipping_address);
				@endphp
				<tr><td class="strong small gry-color">{{ translate('Bill to') }}:</td></tr>
				<tr><td class="strong">{{ $shipping_address->name }}</td></tr>
				<tr><td class="gry-color small">{{ $shipping_address->address }}, {{ $shipping_address->city }}, {{ \App\Country::where('id',$shipping_address->country)->value('name_'.locale()) }}</td></tr>
				<tr><td class="gry-color small">{{ translate('Email') }}: {{ $shipping_address->email }}</td></tr>
				<tr><td class="gry-color small">{{ translate('Phone') }}: {{ $shipping_address->phone }}</td></tr>
			</table>
		</div>

	    <div style="padding: 1.5rem;">
			<table class="padding text-left small border-bottom">
				<thead>
	                <tr class="gry-color" style="background: #eceff4;">
	                    <th width="35%">{{ translate('Product Name') }}</th>
						<th width="15%">{{ translate('Delivery Type') }}</th>
	                    <th width="10%">{{ translate('Qty') }}</th>
	                    <th width="15%">{{ translate('Unit Price') }}</th>
	                    <th width="10%">{{ translate('Tax') }}</th>
	                    <th width="15%" class="text-right">{{ translate('Total') }}</th>
	                </tr>
				</thead>
				<tbody class="strong">
	                @foreach ($order->orderDetails as $key => $orderDetail)
		                @if ($orderDetail->product != null)
		                @php
                            $name = 'name_'.locale();
                        @endphp
							<tr class="">
								<td>{{ $orderDetail->product->{'name_'.locale()} }} @if($orderDetail->variation != null) ({{ $orderDetail->variation }}) @endif</td>
								<td>
									@if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
										{{ translate('Home Delivery') }}
									@elseif ($orderDetail->shipping_type == 'pickup_point')
										@if ($orderDetail->pickup_point != null)
											{{ $orderDetail->pickup_point->name }} ({{ translate('Pickip Point') }})
										@endif
									@endif
								</td>
								<td class="gry-color">{{ $orderDetail->quantity }}</td>
								<td class="gry-color currency">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
								<td class="gry-color currency">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td>
			                    <td class="text-right currency">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
							</tr>
		                @endif
					@endforeach
	            </tbody>
			</table>
		</div>

	    <div style="padding:0 1.5rem;">
	        <table style="width: 40%;margin-right:auto;" class="text-right sm-padding small strong">
		        <tbody>
			        <tr>
			            <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
			            <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}</td>
			        </tr>
			        <tr>
			            <th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
			            <td class="currency">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
			        </tr>
			        <tr class="border-bottom">
			            <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
			            <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
			        </tr>
			        <tr>
			            <th class="text-left strong">{{ translate('Grand Total') }}</th>
			            <td class="currency">{{ single_price($order->grand_total) }}</td>
			        </tr>
		        </tbody>
		    </table>
	    </div>


	     <br>
	    <br>
	    <br>
	    <br><br><br>
	    <br>
	    <br>

	    <hr>

	    

	    <div style="padding:0 1.5rem;padding-bottom: 0;display: block;position: absolute;">
	        <table class="sm-padding small strong">
	        	<thead>
	                <tr>
	                    <th width="35%" style="font-size: 18px;font-weight: 600;">{{  translate('Instructions') }}</th>
	                </tr>
				</thead>
		        <tbody>
			        <tr>
			            <td>{{ $instruction }}</td>
			        </tr>
			     </tbody> 
			   </table> 	
	    </div>
	    
	</div>
</body>
</html>
