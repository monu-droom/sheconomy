@extends('frontend.layouts.app')

@section('content')

<section class="gry-bg py-4">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="p-4 bg-white">
                    <div class="container">
                        <div class="container-fluid p-0">
                            <section class="row no-gutters align-items-center">
                            <h2>Contact Us</h2>
                            <div class="col-12 text-center d-flex">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3506.5165683916034!2d77.29953031460958!3d28.494102097084543!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce71f2532e03f%3A0xb4977e702a881240!2sSHEconomy!5e0!3m2!1sen!2sin!4v1608812174118!5m2!1sen!2sin" width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                            </div>
                            </section>
                        </div>
                    </div>
                    <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-4">
                                <h4>Contact Us</h4>
                            </div>
                            <form method="post" action="/send-contact-us" data-form-title="Contact Us">
                                @csrf
                                <input type="hidden" data-form-email="true">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" required="" placeholder="Name*" data-form-field="Name">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" required="" placeholder="Email*" data-form-field="Email">
                                    </div>
                                    <div class="form-group">
                                        <input type="tel" class="form-control" name="phone" placeholder="Phone" data-form-field="Phone">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="message" placeholder="Message" rows="7" data-form-field="Message"></textarea>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn  btn-danger">Send</button>
                                    </div>
                                </form>
                            </div>
                            <div class="offset-md-1 col-md-5">
                                <div class="mt-4">
                                <h4>Address</h4>
                                </div>
                                <div style="border: 2px dashed #e43c67; padding: 10px;">
                                    <div>
                                        <h6>Sheconomy Pvt. Ltd.</h6>
                                        <div>
                                            B-II/66, MCIE, Delhi Mathura Road, New Delhi
                                        </div>
                                        <div>
                                            Badrpur Delhi, 110020, India
                                        </div>
                                        <div>
                                            <strong>Email: </strong> sellers@sheconomy.in
                                        </div>
                                        <div>
                                            <strong>Mobile: </strong> +91 8700297617
                                        </div>
                                        <div>
                                            <strong>CIN No.</strong> U74999DL2020PTC365879
                                        </div>
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
