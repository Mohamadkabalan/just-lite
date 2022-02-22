@php $lang=app()->getLocale();  @endphp
@extends('layouts.master')

@section('page_title')
    Shop
@endsection

@section('head')

@endsection

@section('seo')

@stop

@section('content-wrapper')
    @include('home.navbar')
    <!-- Inner Banner -->
    <div class="inner-banner inner-bg3">
        <div class="container">
            <div class="inner-title text-center">
                <h3>Shop</h3>
                <ul>
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>Shop</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Banner End -->

    <!-- Product Area -->
    <div class="product-area pt-100 pb-70">
        <div class="container">
            <div class="product-topper">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-md-7">
                        <div class="product-title">
                            <h3>Showing 1-9 of 40 results</h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-5">
                        <div class="product-list">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Default Price</option>
                                <option value="1">Price High To Low</option>
                                <option value="2">Price Low To High</option>
                            </select>
                            <i class='bx bx-chevron-down'></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-45">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-img">
                                    <a href="shop-details.html">
                                        <img src="assets/images/product-img/product-img1.png" alt="Product Images">
                                    </a>

                                    <ul class="product-item-action">
                                        <li><a href="wishlist.html"><i class='bx bx-heart'></i></a></li>
                                        <li><a href="cart.html"><i class='bx bx-cart'></i></a></li>
                                    </ul>
                                </div>
                                <div class="content">
                                    <h3><a href="shop-details.html">Lemon</a></h3>
                                    <span>$50.00</span>
                                    <div class="rating">
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-img">
                                    <a href="shop-details.html">
                                        <img src="assets/images/product-img/product-img2.png" alt="Product Images">
                                    </a>

                                    <ul class="product-item-action">
                                        <li><a href="wishlist.html"><i class='bx bx-heart'></i></a></li>
                                        <li><a href="cart.html"><i class='bx bx-cart'></i></a></li>
                                    </ul>
                                </div>
                                <div class="content">
                                    <h3><a href="shop-details.html">Apple</a></h3>
                                    <span>$30.00</span>
                                    <div class="rating">
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-img">
                                    <a href="shop-details.html">
                                        <img src="assets/images/product-img/product-img3.png" alt="Product Images">
                                    </a>

                                    <ul class="product-item-action">
                                        <li><a href="wishlist.html"><i class='bx bx-heart'></i></a></li>
                                        <li><a href="cart.html"><i class='bx bx-cart'></i></a></li>
                                    </ul>
                                </div>
                                <div class="content">
                                    <h3><a href="shop-details.html">Avocado</a></h3>
                                    <span>$60.00</span>
                                    <div class="rating">
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-img">
                                    <a href="shop-details.html">
                                        <img src="assets/images/product-img/product-img4.png" alt="Product Images">
                                    </a>

                                    <ul class="product-item-action">
                                        <li><a href="wishlist.html"><i class='bx bx-heart'></i></a></li>
                                        <li><a href="cart.html"><i class='bx bx-cart'></i></a></li>
                                    </ul>
                                </div>
                                <div class="content">
                                    <h3><a href="shop-details.html">Watermelon</a></h3>
                                    <span>$20.00</span>
                                    <div class="rating">
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-img">
                                    <a href="shop-details.html">
                                        <img src="assets/images/product-img/product-img5.png" alt="Product Images">
                                    </a>

                                    <ul class="product-item-action">
                                        <li><a href="wishlist.html"><i class='bx bx-heart'></i></a></li>
                                        <li><a href="cart.html"><i class='bx bx-cart'></i></a></li>
                                    </ul>
                                </div>
                                <div class="content">
                                    <h3><a href="shop-details.html">Pomegranate</a></h3>
                                    <span>$40.00</span>
                                    <div class="rating">
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-img">
                                    <a href="shop-details.html">
                                        <img src="assets/images/product-img/product-img6.png" alt="Product Images">
                                    </a>

                                    <ul class="product-item-action">
                                        <li><a href="wishlist.html"><i class='bx bx-heart'></i></a></li>
                                        <li><a href="cart.html"><i class='bx bx-cart'></i></a></li>
                                    </ul>
                                </div>
                                <div class="content">
                                    <h3><a href="shop-details.html">Capsicum</a></h3>
                                    <span>$70.00</span>
                                    <div class="rating">
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                        <i class='bx bxs-star'></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 text-center">
                            <div class="pagination-area">
                                <a href="shop.html" class="prev page-numbers">
                                    <i class="bx bx-chevron-left"></i>
                                </a>

                                <span class="page-numbers current" aria-current="page">1</span>
                                <a href="shop.html" class="page-numbers">2</a>
                                <a href="shop.html" class="page-numbers">3</a>

                                <a href="shop.html" class="next page-numbers">
                                    <i class="bx bx-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="side-bar-area pl-20">
                        <div class="recipe-side-bar-categories">
                            <h3>Categories</h3>
                            <ul>
                                <li>
                                    <a href="categories.html" target="_blank">
                                        Dinner
                                        <span>(09)</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="categories.html" target="_blank">
                                        Dessert
                                        <span>(10)</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="categories.html" target="_blank">
                                        Pasta
                                        <span>(12)</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="categories.html" target="_blank">
                                        Cake
                                        <span>(09)</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="categories.html" target="_blank">
                                        Fruits
                                        <span>(08)</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="categories.html" target="_blank">
                                        Soft drinks
                                        <span> (05)</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Area End -->

@endsection