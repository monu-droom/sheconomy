@extends('frontend.layouts.app')

@section('content')
<style>
    @media(max-width: 500px) {
        div.scroll {  
        margin: 4px, 4px;  
        padding: 4px;  
        width: 300px;  
        overflow: auto;  
        white-space: nowrap;  
    } 
    }
    #no-crying_1 {
      display: none;
    }
    #no-crying_2 {
      display: none;
    }
    #no-crying_3 {
      display: none;
    }

    #no-crying_4 {
      display: none;
    }
    .hide {
      display: none;
    }

    .map-container {
      text-align: center;
    }

    .map-point-sm{
      border: none;
      background-color: snow;
    }
  </style>
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{ translate('Create Visiting Cards')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li class="active"><a href="">{{ translate('Visiting Card')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="map-container">
                            <div class="inner-basic division-map div-toggle" data-target=".division-details" id="divisiondetail">
                            <button class="map-point-sm active" data-show=".one">
                                <div class="content">
                                <div class="centered-y">
                                    <img style="height: 100px; width: 100px; border-radius: 8px;" onclick="getFirst()" src="{{ my_asset('cards/1.jpg') }}" alt="" class="img-responsive">
                                </div>
                                </div>
                            </button>
                            <button class="map-point-sm" data-show=".two">
                                <div class="content">
                                <div class="centered-y">
                                    <img style="height: 100px; width: 100px; border-radius: 8px;" onclick="getSecond()" src="{{ my_asset('cards/2.jpg') }}" alt="" class="img-responsive">
                                </div>
                                </div>
                            </button>
                            <button class="map-point-sm" data-show=".three">
                                <div class="content">
                                <div class="centered-y">
                                    <img style="height: 100px; width: 100px; border-radius: 8px;" onclick="getThird()" src="{{ my_asset('cards/3.jpg') }}" alt="" class="img-responsive">
                                </div>
                                </div>
                            </button>
                            <button class="map-point-sm" data-show=".four">
                                <div class="content">
                                <div class="centered-y">
                                    <img style="height: 100px; width: 100px; border-radius: 8px;" onclick="getFourth();" src="{{ my_asset('cards/4.jpg') }}" alt="" class="img-responsive">
                                </div>
                                </div>
                            </button>
                            </div><!-- end inner basic -->
                        </div>
                    <!--<div class="container mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter Address" class="form-control copy-text-1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter Mobile Number" class="form-control copy-text-2">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-danger" id="submit">Submit</button>
                            </div>
                        </div> -->

                        <div class="map-container">
                            <div class="inner-basic division-details">
                                <div class="initialmsg">
                                    <p>Choose image above</p>
                                </div>

                                @if($seller->visiting_cards != '')
                                <div>
                                    <img src="{{ $seller->visiting_cards }}" alt="Your Visiting Card" id="img">
                                </div>
                                <div class="mt-2">
                                    <a download="visiting-card.png" class="btn btn-danger" href="{{ $seller->visiting_cards }}">Download</a>
                                </div>    
                                @endif

                                <div class="one hide mt-4">
                                    <div class="container border p-4">
                                        <div class="mt-4">
                                            <form action="">
                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                                <div class="row">
                                                    <div class="col-md-4 border-right">
                                                        <div class="form-group">
                                                            <h4>Fill your details</h4>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <textarea id="input-text1" name="" style="white-space: pre-wrap;" rows=3 cols="13" onkeyup="usertextChange1(this.value)" class="form-control" autocomplete="off" placeholder="Your Address" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input id="input-text2" type="text" placeholder="Your Phone" onkeyup="usertextChange2(this.value)" class="form-control" autocomplete="off" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input id="input-text3" type="text" placeholder="Your Email" onkeyup="usertextChange3(this.value)" class="form-control" autocomplete="off" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <input id="input-text4" type="text" placeholder="Your Website" onkeyup="usertextChange4(this.value)" class="form-control" autocomplete="off" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="imageLoader" required/>
                                                                    <label class="custom-file-label" for="validatedCustomFile">Upload Your Logo</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <img src="" hidden id="img" alt="">
                                                        <button id="save1" class="btn btn-danger">Save</button>
                                                        <input type="reset" class="btn btn-secondary" id="resetFirst" value="Reset">
                                                        </div>
                                                        <div class="col-md-8">
                                                            <img id="no-crying_1" src="{{ my_asset('cards/1.jpg') }}"/>
                                                            <div class="art-container scroll">
                                                                <!-- <canvas id="canvas"  height="352" width="600" ></canvas> -->
                                                                <canvas name="visiting_cards" height="352" width="600" id="c1"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-danger" onclick="download_image()">Download</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="two hide mt-4">
                                        <div class="container border p-4">
                                            <div class="mt-4">
                                                <div class="row">
                                                    <div class="col-md-4 border-right">
                                                        <form action="">
                                                            <div class="form-group">
                                                                <h4>Fill your details</h4>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <textarea id="input-text5" name="" style="white-space: pre-wrap;" rows=3 cols="13" onkeyup="usertextChange5(this.value)" class="form-control" autocomplete="off" placeholder="Your Address"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text6" type="text" placeholder="Your Phone" onkeyup="usertextChange6(this.value)" class="form-control" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text7" type="text" placeholder="Your Email" onkeyup="usertextChange7(this.value)" class="form-control" autocomplete="off" requried>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text8" type="text" placeholder="Your Website" onkeyup="usertextChange8(this.value)" class="form-control" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="imageLoader_2" name="imageLoader_2" required/>
                                                                <label class="custom-file-label" for="validatedCustomFile">Upload Your Logo</label>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <img src="" hidden id="img2" alt="">
                                                            <button id="save2" class="btn btn-danger">Save</button>
                                                            <input type="reset" class="btn btn-secondary" id="resetTwo" value="Reset">
                                                        </form>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <img id="no-crying_2" src="{{ my_asset('cards/2.jpg') }}"/>
                                                        <div class="art-container scroll">
                                                            <!-- <canvas id="canvas"  height="352" width="600" ></canvas> -->
                                                            <canvas name="visiting_cards[]" height="352" width="600" id="c2"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="form-group">
                                                    <button class="btn btn-danger" onclick="download_image_2()">Download</button>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="three hide mt-4">
                                        <div class="container border p-4">
                                            <div class="mt-4">
                                                <div class="row">
                                                    <div class="col-md-4 border-right">
                                                        <form action="">
                                                            <div class="form-group">
                                                                <h4>Fill your details</h4>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <textarea id="input-text9" name="" style="white-space: pre-wrap;" rows=3 cols="13" onkeyup="usertextChange9(this.value)" class="form-control" autocomplete="off" required placeholder="Your Address"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text10" type="text" placeholder="Your Phone" onkeyup="usertextChange10(this.value)" class="form-control" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text11" type="text" placeholder="Your Email" onkeyup="usertextChange11(this.value)" class="form-control" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text12" type="text" placeholder="Your Website" onkeyup="usertextChange12(this.value)" class="form-control" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="imageLoader_3" name="imageLoader_3" required/>
                                                                <label class="custom-file-label" for="validatedCustomFile">Upload Your Logo</label>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <img src="" hidden id="img3" alt="">
                                                            <button id="save3" class="btn btn-danger">Save</button>
                                                            <input type="reset" class="btn btn-secondary" id="resetThree" value="Reset">
                                                        </form>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <img id="no-crying_3" src="{{ my_asset('cards/3.jpg') }}"/>
                                                        <div class="art-container scroll">
                                                            <!-- <canvas id="canvas"  height="352" width="600" ></canvas> -->
                                                            <canvas height="352" width="600" id="c3"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="form-group">
                                                    <button class="btn btn-danger" onclick="download_image_3()">Download</button>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="four hide mt-4">
                                        <div class="container border p-4">
                                            <div class="mt-4">
                                                <div class="row">
                                                    <div class="col-md-4 border-right">
                                                        <form action="">
                                                            <div class="form-group">
                                                                <h4>Fill your details</h4>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <textarea id="input-text13" name="" style="white-space: pre-wrap;" rows=3 cols="13" onkeyup="usertextChange13(this.value)" class="form-control" autocomplete="off" required placeholder="Your Address"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text14" type="text" placeholder="Your Phone" onkeyup="usertextChange14(this.value)" class="form-control" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text15" type="text" placeholder="Your Email" onkeyup="usertextChange15(this.value)" class="form-control" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <input id="input-text16" type="text" placeholder="Your Website" onkeyup="usertextChange16(this.value)" class="form-control" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="imageLoader_4" name="imageLoader_4" required/>
                                                                <label class="custom-file-label" for="validatedCustomFile">Upload Your Logo</label>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <img src="" hidden id="img4" alt="">
                                                            <button id="save4" class="btn btn-danger">Save</button>
                                                            <input type="reset" class="btn btn-secondary" id="resetFour" value="Reset">
                                                        </form>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <img id="no-crying_4" src="{{ my_asset('cards/4.jpg') }}"/>
                                                        <div class="art-container scroll">
                                                            <!-- <canvas id="canvas"  height="352" width="600" ></canvas> -->
                                                            <canvas height="352" width="600" id="c4"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-danger" onclick="download_image_4()">Download</button>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
    @section('script')
    <script>
        $(document).on('click', '.map-point-sm', function() {
            var show = $(this).data('show');
            $(show).removeClass("hide").siblings().addClass("hide");
            });

    </script>

    <script>
        function getFirst(){
        var canvas_1 = document.getElementById('c1');
        var context_1 = canvas_1.getContext('2d');
        var maxWidth_1 = 90;
        var lineHeight_1 = 45;
        var x = 35;
        var y = 315;
        var text1 = document.getElementById('input-text1').value;
        var text2 = document.getElementById('input-text2').value;
        var text3 = document.getElementById('input-text3').value;
        var text4 = document.getElementById('input-text4').value;
        
        function drawImage(text1, text2, text3, text4) {
            var canvas_1 = document.getElementById('c1');
            var context_1 = canvas_1.getContext('2d');
            context_1.clearRect(0, 0, canvas_1.width, canvas_1.height);
            var img_1 = document.getElementById('no-crying_1');  
            context_1.drawImage(img_1, 0, 0, canvas_1.width, canvas_1.height);
        }               
        function wrapText(context_1, text1, x, y, maxWidth, lineHeight) {
        var words_1 = text1.split(' ');
        var line_1 = '';
        
        for(var n = 0; n < words_1.length; n++) {
            var testLine_1 = line_1 + words_1[n] + ' ';
            var metrics_1 = context_1.measureText(testLine_1);
            var testWidth_1 = metrics_1.width;
            if (testWidth_1 > maxWidth_1 && n > 0) {
                context_1.fillText(line_1, x, y);
                line_1 = words_1[n] + ' ';
                y += lineHeight_1;
            }
            else {
                line_1 = testLine_1;
            }
            }
            context_1.fillText(line_1, x, y);
        }
        window.onload = function() {
            drawImage();
        }
        
        // USER IMPUT FUNCTIONS
            window.usertextChange1 = function(val){
                context_1.clearRect(0, 0, canvas_1.width, canvas_1.height);
                context_1.restore();
                drawImage();
                context_1.font = '1 14px Helvetica';
                context_1.fillStyle = "#fff";
                context_1.fillText(val, 240, 257);
                wrapText(context_1, text1, x, y, maxWidth_1, lineHeight_1);
            } 
            window.usertextChange2 = function(val2){
                context_1.restore();
                context_1.font = '1 14px Helvetica';
                context_1.fillStyle = "#fff";
                context_1.fillText(val2, 240, 290);
                wrapText(context_1, text2, x, y, maxWidth_1, lineHeight_1);
                } 
            window.usertextChange3 = function(val){
                context_1.restore();
                context_1.font = '1 14px Helvetica';
                context_1.fillStyle = "#fff";
                context_1.fillText(val, 370, 290);
                wrapText(context_1, text3, x, y, maxWidth_1, lineHeight_1);
            } 
            window.usertextChange4 = function(val){
                context_1.restore();
                context_1.font = '1 14px Helvetica';
                context_1.fillStyle = "#fff";
                context_1.fillText(val, 240, 314);

                wrapText(context_1, text4, x, y, maxWidth_1, lineHeight_1);
            
            }
            // For image upload
            var imageLoader = document.getElementById('imageLoader');
            imageLoader.addEventListener('change', handleImage, false);
            var canvas_1 = document.getElementById('c1');
            var context_1 = canvas_1.getContext('2d');
            function handleImage(e){
                var reader = new FileReader();
                reader.onload = function(event){
                    var img = new Image();
                    img.onload = function(){
                        context_1.drawImage(img,350, 30, 120, 80);
                    }
                    img.src = event.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);     
            }


            //Save Image---------------
            $('#save1').click(function(){
                success = 1;
                // event.preventDefault();
                var canvas = document.getElementById("c1");
                image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                document.getElementById('img').setAttribute('src', image);          
                $.post('{{ route("saveImage") }}', {_token:'{{ csrf_token() }}', image, success});
                if(success == 1){
                    alert('Your visiting card is ready.');
                    showFrontendAlert('success', image.message);
                }
            });          

            $("#resetFirst").click(function () {
                drawImage();
            });
        }    
        </script>

        <script>
        function getSecond(){
        var canvas_2 = document.getElementById('c2');
        var context_2 = canvas_2.getContext('2d');
        var maxWidth_2 = 90;
        var lineHeight_2 = 45;
        var x = 35;
        var y = 315;
        var text1 = document.getElementById('input-text5').value;
        var text2 = document.getElementById('input-text6').value;
        var text3 = document.getElementById('input-text7').value;
        var text4 = document.getElementById('input-text8').value;
        
        function drawImage(text1) {
            var canvas_2 = document.getElementById('c2');
            var context_2 = canvas_2.getContext('2d');
            context_2.clearRect(240, 314, canvas_2.width, canvas_2.height);
            var img_2 = document.getElementById('no-crying_2');  
            context_2.drawImage(img_2, 0, 0, canvas_2.width, canvas_2.height);
        }               
        function wrapText(context_2, text1, x, y, maxWidth_2, lineHeight_2) {
        var words_2 = text1.split(' ');
        var line_2 = '';
        
        for(var n = 0; n < words_2.length; n++) {
            var testLine_2 = line_2 + words_2[n] + ' ';
            var metrics_2 = context_2.measureText(testLine_2);
            var testWidth_2 = metrics_2.width;
                if (testWidth_2 > maxWidth_2 && n > 0) {
                context_2.fillText(line_2, x, y);
                line_2 = words_2[n] + ' ';
                y += lineHeight_2;
                }
                else {
                line_2 = testLine_2;
                }
            }
            context_2.fillText(line_2, x, y);
            }
            window.onload = function() {
            drawImage();
            }
        
            // USER IMPUT FUNCTIONS
            window.usertextChange5 = function(val){
                context_2.clearRect(0, 0, canvas_2.width, canvas_2.height);
                context_2.restore();
                drawImage();
                context_2.font = '14px Helvetica';
                context_2.fillStyle = "#000";
                context_2.fillText(val, 60, 277);
                wrapText(context_2, text1, x, y, maxWidth_2, lineHeight_2);
            } 
            window.usertextChange6 = function(val){
                context_2.restore();
                context_2.font = '14px Helvetica';
                context_2.fillStyle = "#000";
                context_2.fillText(val, 450, 300);
                wrapText(context_2, text1, x, y, maxWidth_2, lineHeight_2);
                } 
            window.usertextChange7 = function(val){
                context_2.restore();
                context_2.font = '14px Helvetica';
                context_2.fillStyle = "#000";
                context_2.fillText(val, 60, 310);
                wrapText(context_2, text1, x, y, maxWidth_2, lineHeight_2);
            } 
            window.usertextChange8 = function(val){
                context_2.restore();
                context_2.font = '14px Helvetica';
                context_2.fillStyle = "#000";
                context_2.fillText(val, 60, 340);

                wrapText(context_2, text1, x, y, maxWidth_2, lineHeight_2);
                
            }
            // For image upload
            var imageLoader_2 = document.getElementById('imageLoader_2');
                imageLoader_2.addEventListener('change', handleImage, false);
                var canvas_2 = document.getElementById('c2');
                var context_2 = canvas_2.getContext('2d');
                function handleImage(e){
                    var reader_2 = new FileReader();
                    reader_2.onload = function(event){
                        var img_2 = new Image();
                        img_2.onload = function(){
                            context_2.drawImage(img_2,240, 90, 120, 80);
                        }
                        img_2.src = event.target.result;
                    }
                    reader_2.readAsDataURL(e.target.files[0]);     
                }

            //Save Image---------------
            $('#save2').click(function(){
                success = 1;
                // event.preventDefault();
                var canvas = document.getElementById("c2");
                image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                document.getElementById('img2').setAttribute('src', image);          
                $.post('{{ route("saveImage") }}', {_token:'{{ csrf_token() }}', image, success});
                if(success == 1){
                    alert('Your visiting card is ready.');
                    showFrontendAlert('success', image.message);
                }
            }); 

            $("#resetSecond").click(function () {
                drawImage();
            });
            }
        </script>



        <script>
        function getThird(){
        var canvas_3 = document.getElementById('c3');
        var context_3 = canvas_3.getContext('2d');
        var maxWidth_3 = 90;
        var lineHeight_3 = 45;
        var x = 35;
        var y = 315;
        var text1 = document.getElementById('input-text9').value;
        var text2 = document.getElementById('input-text10').value;
        var text3 = document.getElementById('input-text11').value;
        var text4 = document.getElementById('input-text12').value;
        
        function drawImage(text1) {
            var canvas_3 = document.getElementById('c3');
            var context_3 = canvas_3.getContext('2d');
            context_3.clearRect(240, 314, canvas_3.width, canvas_3.height);
            var img_3 = document.getElementById('no-crying_3');  
            context_3.drawImage(img_3, 0, 0, canvas_3.width, canvas_3.height);
        }               
        function wrapText(context_3, text1, x, y, maxWidth_3, lineHeight_3) {
        var words_3 = text1.split(' ');
        var line_3 = '';
        
        for(var n = 0; n < words_3.length; n++) {
            var testLine_3 = line_3 + words_3[n] + ' ';
            var metrics_3 = context_3.measureText(testLine_3);
            var testWidth_3 = metrics_3.width;
                if (testWidth_3 > maxWidth_3 && n > 0) {
                context_3.fillText(line_3, x, y);
                line_3 = words_3[n] + ' ';
                y += lineHeight_3;
                }
                else {
                line_3 = testLine_3;
                }
            }
            context_3.fillText(line_3, x, y);
            }
            window.onload = function() {
            drawImage();
            }
        
            // USER IMPUT FUNCTIONS
            window.usertextChange9 = function(val){
                context_3.clearRect(0, 0, canvas_3.width, canvas_3.height);
                context_3.restore();
                drawImage();
                context_3.font = '14px Helvetica';
                context_3.fillStyle = "#000";
                context_3.fillText(val, 310, 285);
                wrapText(context_3, text1, x, y, maxWidth_3, lineHeight_3);
            } 
            window.usertextChange10 = function(val){
                context_3.restore();
                context_3.font = '14px Helvetica';
                context_3.fillStyle = "#000";
                context_3.fillText(val, 70, 275);
                wrapText(context_3, text1, x, y, maxWidth_3, lineHeight_3);
                } 
            window.usertextChange11 = function(val){
                context_3.restore();
                context_3.font = '14px Helvetica';
                context_3.fillStyle = "#000";
                context_3.fillText(val, 70, 300);
                wrapText(context_3, text1, x, y, maxWidth_3, lineHeight_3);
            } 
            window.usertextChange12 = function(val){
                context_3.restore();
                context_3.font = '14px Helvetica';
                context_3.fillStyle = "#000";
                context_3.fillText(val, 70, 328);

                wrapText(context_3, text1, x, y, maxWidth_3, lineHeight_3);
                
            }
            // For image upload
            var imageLoader_3 = document.getElementById('imageLoader_3');
                imageLoader_3.addEventListener('change', handleImage, false);
                var canvas_3 = document.getElementById('c3');
                var context_3 = canvas_3.getContext('2d');
                function handleImage(e){
                    var reader_3 = new FileReader();
                    reader_3.onload = function(event){
                        var img_3 = new Image();
                        img_3.onload = function(){
                            context_3.drawImage(img_3, 70, 80, 120, 80);
                        }
                        img_3.src = event.target.result;
                    }
                    reader_3.readAsDataURL(e.target.files[0]);     
                }

            //Save Image---------------
            $('#save3').click(function(){
                success = 1;
                // event.preventDefault();
                var canvas = document.getElementById("c3");
                image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                document.getElementById('img3').setAttribute('src', image);          
                $.post('{{ route("saveImage") }}', {_token:'{{ csrf_token() }}', image, success});
                if(success == 1){
                    alert('Your visiting card is ready.');
                    showFrontendAlert('success', image.message);
                }
            }); 
            $("#resetThird").click(function () {
                drawImage();
            });
            }
        </script>
        
        <script>
            function getFourth(){
            var canvas_4 = document.getElementById('c4');
            var context_4 = canvas_4.getContext('2d');
            var maxWidth_4 = 90;
            var lineHeight_4 = 45;
            var x = 35;
            var y = 315;
            var text1 = document.getElementById('input-text13').value;
            var text2 = document.getElementById('input-text14').value;
            var text3 = document.getElementById('input-text15').value;
            var text4 = document.getElementById('input-text16').value;
            
            function drawImage(text1) {
                var canvas_4 = document.getElementById('c4');
                var context_4 = canvas_4.getContext('2d');
                context_4.clearRect(300, 314, canvas_4.width, canvas_4.height);
                var img_4 = document.getElementById('no-crying_4');  
                context_4.drawImage(img_4, 0, 0, canvas_4.width, canvas_4.height);
            }               
            function wrapText(context_4, text1, x, y, maxWidth_4, lineHeight_4) {
            var words_4 = text1.split(' ');
            var line_4 = '';
            
            for(var n = 0; n < words_4.length; n++) {
                var testLine_4 = line_4 + words_4[n] + ' ';
                var metrics_4 = context_4.measureText(testLine_4);
                var testWidth_4 = metrics_4.width;
                if (testWidth_4 > maxWidth_4 && n > 0) {
                    context_4.fillText(line_4, x, y);
                    line_4 = words_4[n] + ' ';
                    y += lineHeight_4;
                }
                else {
                    line_4 = testLine_4;
                }
                }
                context_4.fillText(line_4, x, y);
            }
            window.onload = function() {
                drawImage();
            }
            
            // USER IMPUT FUNCTIONS
                window.usertextChange13 = function(val){
                    context_4.clearRect(0, 0, canvas_4.width, canvas_4.height);
                    context_4.restore();
                    drawImage();
                    context_4.font = '14px Helvetica';
                    context_4.fillStyle = "#000";
                    context_4.fillText(val, 380, 220);
                    wrapText(context_4, text1, x, y, maxWidth_4, lineHeight_4);
                } 
                window.usertextChange14 = function(val){
                    context_4.restore();
                    context_4.font = '14px Helvetica';
                    context_4.fillStyle = "#000";
                    context_4.fillText(val, 380, 265);
                    wrapText(context_4, text1, x, y, maxWidth_4, lineHeight_4);
                    } 
                window.usertextChange15 = function(val){
                    context_4.restore();
                    context_4.font = '14px Helvetica';
                    context_4.fillStyle = "#000";
                    context_4.fillText(val, 380, 295);
                    wrapText(context_4, text1, x, y, maxWidth_4, lineHeight_4);
                } 
                window.usertextChange16 = function(val){
                    context_4.restore();
                    context_4.font = '14px Helvetica';
                    context_4.fillStyle = "#000";
                    context_4.fillText(val, 380, 324);

                    wrapText(context_4, text1, x, y, maxWidth_4, lineHeight_4);
                
                }
                // For image upload
            var imageLoader_4 = document.getElementById('imageLoader_4');
                imageLoader_4.addEventListener('change', handleImage, false);
                var canvas_4 = document.getElementById('c4');
                var context_4 = canvas_4.getContext('2d');
                function handleImage(e){
                    var reader_4 = new FileReader();
                    reader_4.onload = function(event){
                        var img_4 = new Image();
                        img_4.onload = function(){
                            context_4.drawImage(img_4, 80, 80, 120, 80);
                        }
                        img_4.src = event.target.result;
                    }
                    reader_4.readAsDataURL(e.target.files[0]);     
                }

            //Save Image---------------
            $('#save4').click(function(){
                success = 1;
                // event.preventDefault();
                var canvas = document.getElementById("c4");
                image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                document.getElementById('img4').setAttribute('src', image);          
                $.post('{{ route("saveImage") }}', {_token:'{{ csrf_token() }}', image, success});
                if(success == 1){
                    alert('Your visiting card is ready.');
                    showFrontendAlert('success', image.message);
                }
            }); 
                $("#resetFourth").click(function () {
                    drawImage();
                });
            }
            </script>
            <!-- <script>
            $(function(){
                var $copy_text_1 = $('.copy-text-1');
                var $copy_text_2 = $('.copy-text-2');
                var $copy_text_3 = $('.copy-text-3');
                var $copy_text_4 = $('.copy-text-4');
                var $input_text1 = $('#input-text1');
                var $input_text2 = $('#input-text2');
                var $input_text3 = $('#input-text3');
                var $input_text4 = $('#input-text4');
                    $("#submit").click(function(){
                        $input_text1.val($copy_text_1.val());
                        $input_text2.val($copy_text_2.val());
                        $input_text3.val($copy_text_3.val());
                        $input_text4.val($copy_text_4.val());
                    });     -->
                });
            </script>

            <script>
            // Downloading canvas image 1
            function download_image(){
                var canvas = document.getElementById("c1");
                image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                var link = document.createElement('a');
                link.download = "my-image.png";
                link.href = image;
                link.click();
            }

            // Downloading canvas image 2
            function download_image_2(){
                var canvas = document.getElementById("c2");
                image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                var link = document.createElement('a');
                link.download = "my-image.png";
                link.href = image;
                link.click();
            }

            // Downloading canvas image 3
            function download_image_3(){
                var canvas = document.getElementById("c3");
                image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                var link = document.createElement('a');
                link.download = "my-image.png";
                link.href = image;
                link.click();
            }

            // Downloading canvas image 4
            function download_image_4(){
                var canvas = document.getElementById("c4");
                image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
                var link = document.createElement('a');
                link.download = "my-image.png";
                link.href = image;
                link.click();
            }
            </script>
    @endsection