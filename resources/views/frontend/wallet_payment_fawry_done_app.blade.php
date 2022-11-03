@extends('frontend.layouts.empty')
<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md text-center">
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            @if ($lang == 'ar')
                <h4 class="text-center"> تم الشحن بنجاح وفى انتظار الدفع </h4>

            @else
                <h4 class="text-center"> Charged successfuly and waiting for payment</h4>

            @endif
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
