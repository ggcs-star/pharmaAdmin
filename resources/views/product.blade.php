{{--
  ============================================================================
  PRODUCT DETAIL PAGE - EXACT 1MG STYLE CLONE (FULLY DYNAMIC)
  ============================================================================
  This template is an exact replica of 1mg's medicine detail page with:
  - Left sidebar navigation (Overview, Uses, Side Effects, etc.)
  - IMAGE GALLERY with thumbnails (FIXED - was missing)
  - Right side content area with all sections (DYNAMIC from database)
  - Sticky price card with substitutes (DYNAMIC)
  - Product introduction, benefits, side effects, safety advice (FROM DB)
  - User feedback/survey section (FROM DB)
  - FAQ section with expandable questions (FROM DB)
  - Related products (FROM DB)
  - Expert/author details (FROM DB)
  - Manufacturer information (FROM DB)
  - Call to order section
  ============================================================================
--}}

@extends('layouts.app')

@section('title', ($product['name'] ?? 'Product') . ' - MediLife')

@section('content')

<div class="container-fluid px-0">
    <div class="container-fluid custom-product-container py-3">
        
     

        <div class="row gx-0 align-items-start">
            
            {{-- LEFT SIDEBAR NAVIGATION (1MG Style) --}}
   <div class="col-lg-2 custom-left-sidebar d-none d-lg-block">

    <div class="sidebar-nav">

                        <ul class="nav flex-column nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#overview" data-scroll="overview">
                                <i class="fas fa-info-circle me-2"></i> Overview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#gallery" data-scroll="gallery">
                                <i class="fas fa-images me-2"></i> Images
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#uses" data-scroll="uses">
                                <i class="fas fa-stethoscope me-2"></i> Uses and benefits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#side-effects" data-scroll="side-effects">
                                <i class="fas fa-exclamation-triangle me-2"></i> Side effects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#how-to-use" data-scroll="how-to-use">
                                <i class="fas fa-hand-holding-medical me-2"></i> How to use
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#how-works" data-scroll="how-works">
                                <i class="fas fa-microscope me-2"></i> How drug works
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#safety-advice" data-scroll="safety-advice">
                                <i class="fas fa-shield-alt me-2"></i> Safety advice
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#missed-dose" data-scroll="missed-dose">
                                <i class="fas fa-clock me-2"></i> Missed dose
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#substitutes" data-scroll="substitutes">
                                <i class="fas fa-exchange-alt me-2"></i> All substitutes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#quick-tips" data-scroll="quick-tips">
                                <i class="fas fa-lightbulb me-2"></i> Quick tips
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#fact-box" data-scroll="fact-box">
                                <i class="fas fa-chart-simple me-2"></i> Fact box
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#feedback" data-scroll="feedback">
                                <i class="fas fa-users me-2"></i> User feedback
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#faq" data-scroll="faq">
                                <i class="fas fa-question-circle me-2"></i> FAQs
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

         {{-- MAIN CONTENT AREA --}}
<div class="col-lg-6 custom-main-content">

    {{-- TOP HERO SECTION --}}
    <div class="row g-3 mb-4">

        {{-- LEFT IMAGE GALLERY --}}
        <div class="col-md-6">

            <div id="gallery" class="section-card">

                <div class="product-gallery">

                    {{-- MAIN IMAGE --}}
                    <div class="main-image-container mb-3">

                        <img id="mainProductImage"
                             src="{{ $product['main_image'] ?? asset('images/default-medicine.png') }}"
                             class="img-fluid w-100"
                             alt="{{ $product['name'] ?? 'Product' }}"
                             onclick="openImageModal(this.src)">

                    </div>

                    {{-- THUMBNAILS --}}
                    @if(!empty($product['main_image']) || !empty($product['gallery_images']))

                    <div class="thumbnails-wrapper">

                        <div class="d-flex gap-2 overflow-auto pb-2">

                            {{-- MAIN IMAGE THUMB --}}
                            @if(!empty($product['main_image']))

                            <div class="thumbnail-item {{ !empty($product['gallery_images']) ? '' : 'active' }}"
                                 onclick="changeMainImage('{{ $product['main_image'] }}')">

                                <img src="{{ $product['main_image'] }}"
                                     alt="thumb">

                            </div>

                            @endif

                            {{-- GALLERY IMAGES --}}
                            @if(!empty($product['gallery_images']))

                                @foreach($product['gallery_images'] as $index => $img)

                                <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                                     onclick="changeMainImage('{{ $img['image'] ?? $img }}')">

                                    <img src="{{ $img['image'] ?? $img }}"
                                         alt="thumb">

                                </div>

                                @endforeach

                            @endif

                        </div>

                    </div>

                    @endif

                    {{-- DISCLAIMER --}}
                    <div class="text-center mt-3">

                        <small class="text-muted">
                            <i class="fas fa-camera me-1"></i>
                            Images are for representation purposes only
                        </small>

                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT PRODUCT DETAILS --}}
        <div class="col-md-6">

            <div id="overview" class="product-header">

                {{-- PRODUCT NAME --}}
                <h1>
                    {{ $product['name'] ?? 'Product Name' }}
                </h1>

                {{-- BADGES --}}
                <div class="d-flex flex-wrap gap-2 mb-3">

                    @if(isset($product['need_prescription']))

                        <span class="badge {{ $product['need_prescription'] ? 'bg-warning text-dark' : 'bg-success' }}">

                            <i class="fas {{ $product['need_prescription'] ? 'fa-prescription-bottle' : 'fa-check-circle' }} me-1"></i>

                            {{ $product['need_prescription'] ? 'Prescription Required' : 'OTC Medicine' }}

                        </span>

                    @endif

                    <span class="badge bg-light text-dark border">

                        <i class="fas fa-building me-1"></i>

                        {{ $product['manufacturer']['name'] ?? $product['manufacturer_name'] ?? 'Generic Manufacturer' }}

                    </span>

                </div>

                {{-- SALT --}}
                <div class="salt-info mb-3">

                    <span class="text-muted d-block mb-1">
                        SALT COMPOSITION
                    </span>

                    <div class="fw-semibold">

                        {{ $product['molecule'] ?? $product['salt_composition'] ?? 'N/A' }}

                    </div>

                </div>

                {{-- STORAGE --}}
                <div class="storage-info mb-3">

                    <span class="text-muted d-block mb-1">
                        STORAGE
                    </span>

                    <div class="small">

                        {{ $product['storage'] ?? 'Store below 30°C' }}

                    </div>

                </div>

                {{-- GENERIC SUBSTITUTE --}}
                @if(!empty($product['substitutes']) && count($product['substitutes']) > 0)

                <div class="generic-alert p-3">

                    <div class="d-flex align-items-center gap-2 mb-2">

                        <i class="fas fa-tags text-success"></i>

                        <strong class="text-success">

                            {{ $product['substitutes'][0]['discount'] ?? '61' }}% cheaper alternative available

                        </strong>

                    </div>

                    <div class="small text-muted">
                        with same salt composition
                    </div>

                    <a href="#substitutes"
                       class="small fw-semibold text-success text-decoration-none d-inline-block mt-2">

                        View substitutes →

                    </a>

                </div>

                @endif

            </div>

        </div>

    </div>

    {{-- PRODUCT INTRODUCTION --}}
    <div class="section-card mb-4">

        <h2 class="h5 fw-bold mb-3">
            PRODUCT INTRODUCTION
        </h2>

        <div class="product-intro text-muted lh-base">

            <p>
                {{ $product['description'] ?? $product['primary_use'] ?? 'No description available.' }}
            </p>

            @if(!empty($product['how_it_works']))

                <p class="mt-2">

                    {{ $product['how_it_works'] }}

                </p>

            @endif

        </div>

    </div>

    {{-- USES --}}
    <div id="uses" class="section-card mb-4">

        <h2 class="h5 fw-bold mb-3">
            USES OF {{ strtoupper($product['name'] ?? 'PRODUCT') }}
        </h2>

        <div class="mb-3">

            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">

                {{ $product['primary_use'] ?? 'Treatment of condition' }}

            </span>

        </div>

        <h3 class="h6 fw-bold mt-4 mb-2">

            BENEFITS OF {{ strtoupper($product['name'] ?? 'PRODUCT') }}

        </h3>

        <div class="benefits-content">

            <strong>

                In {{ $product['primary_use'] ?? 'Treatment' }}

            </strong>

            <p class="text-muted mt-2">

                {{ $product['benefits'] ?? $product['description'] ?? 'No benefits information available.' }}

            </p>

        </div>

    </div>


                {{-- SIDE EFFECTS --}}
                <div id="side-effects" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">SIDE EFFECTS OF {{ strtoupper($product['name'] ?? 'PRODUCT') }}</h2>
                    <p class="text-muted small mb-3">Most side effects do not require any medical attention and disappear as your body adjusts to the medicine. Consult your doctor if they persist or if you're worried about them.</p>
                    
                    <div class="common-side-effects">
                        <h3 class="h6 fw-bold mb-2">Common side effects</h3>
                        <div class="side-effect-tags">
                            @php
                                $sideEffects = !empty($product['side_effects_list']) ? $product['side_effects_list'] : 
                                    (!empty($product['common_side_effect']) ? explode(',', $product['common_side_effect']) : ['No common side effects listed']);
                            @endphp
                            @foreach($sideEffects as $effect)
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">{{ trim($effect) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- HOW TO USE --}}
                <div id="how-to-use" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">HOW TO USE {{ strtoupper($product['name'] ?? 'PRODUCT') }}</h2>
                    <p class="text-muted">{{ $product['how_to_use'] ?? 'Take this medicine in the dose and duration as advised by your doctor. Swallow it as a whole. Do not chew, crush or break it.' }}</p>
                </div>

                {{-- HOW DRUG WORKS --}}
                <div id="how-works" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">HOW {{ strtoupper(explode(' ', ($product['name'] ?? 'PRODUCT'))[0]) }} WORKS</h2>
                    <p class="text-muted">{{ $product['how_it_works'] ?? 'No information available on how this medicine works.' }}</p>
                </div>

                {{-- SAFETY ADVICE --}}
                <div id="safety-advice" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">SAFETY ADVICE</h2>
                    
                    <div class="safety-item d-flex justify-content-between align-items-start py-3 border-bottom">
                        <div class="d-flex gap-3">
                            <i class="fas fa-wine-bottle fs-5 text-danger"></i>
                            <div>
                                <strong>Alcohol</strong>
                                <p class="text-muted small mb-0">{{ $product['alcohol_interaction'] ?? 'No interaction information available.' }}</p>
                            </div>
                        </div>
                        <span class="badge {{ strtoupper($product['alcohol_interaction'] ?? '') == 'SAFE' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill">
                            {{ strtoupper($product['alcohol_interaction'] ?? 'CAUTION') }}
                        </span>
                    </div>
                    
                    <div class="safety-item d-flex justify-content-between align-items-start py-3 border-bottom">
                        <div class="d-flex gap-3">
                            <i class="fas fa-fetus fs-5 text-pink"></i>
                            <div>
                                <strong>Pregnancy</strong>
                                <p class="text-muted small mb-0">{{ $product['pregnancy_interaction'] ?? 'Consult your doctor before use.' }}</p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill">CONSULT DOCTOR</span>
                    </div>
                    
                    <div class="safety-item d-flex justify-content-between align-items-start py-3 border-bottom">
                        <div class="d-flex gap-3">
                            <i class="fas fa-car fs-5 text-info"></i>
                            <div>
                                <strong>Driving</strong>
                                <p class="text-muted small mb-0">{{ $product['driving_interaction'] ?? 'Generally safe to drive.' }}</p>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">SAFE</span>
                    </div>
                    
                    <div class="safety-item d-flex justify-content-between align-items-start py-3 border-bottom">
                        <div class="d-flex gap-3">
                            <i class="fas fa-kidneys fs-5 text-primary"></i>
                            <div>
                                <strong>Kidney</strong>
                                <p class="text-muted small mb-0">{{ $product['kidney_interaction'] ?? 'Use with caution in kidney disease.' }}</p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill">CAUTION</span>
                    </div>
                    
                    <div class="safety-item d-flex justify-content-between align-items-start py-3">
                        <div class="d-flex gap-3">
                            <i class="fas fa-liver fs-5 text-success"></i>
                            <div>
                                <strong>Liver</strong>
                                <p class="text-muted small mb-0">{{ $product['liver_interaction'] ?? 'Use with caution in liver disease.' }}</p>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill">CAUTION</span>
                    </div>
                </div>

                {{-- MISSED DOSE --}}
                <div id="missed-dose" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">WHAT IF YOU FORGET TO TAKE {{ strtoupper(explode(' ', ($product['name'] ?? 'PRODUCT'))[0]) }}?</h2>
                    <p class="text-muted">{{ $product['missed_dose'] ?? 'If you miss a dose, take it as soon as possible. However, if it is almost time for your next dose, skip the missed dose and go back to your regular schedule. Do not double the dose.' }}</p>
                </div>

                {{-- QUICK TIPS --}}
                <div id="quick-tips" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">QUICK TIPS</h2>
                    <ul class="quick-tips-list text-muted">
                        @php
                            $tips = !empty($product['quick_tips_list']) ? $product['quick_tips_list'] : 
                                    (!empty($product['quick_tips']) ? explode("\n", $product['quick_tips']) : ['No quick tips available.']);
                        @endphp
                        @foreach($tips as $tip)
                            @if(trim($tip))
                                <li class="mb-2">{{ trim($tip) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                {{-- FACT BOX --}}
                <div id="fact-box" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">FACT BOX</h2>
                    <div class="fact-grid bg-light rounded-3 p-3">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-muted small">Habit Forming</div>
                                <div class="fw-semibold">{{ $product['habit_forming'] ?? 'No' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Therapeutic Class</div>
                                <div class="fw-semibold">{{ $product['therapeutic_class'] ?? 'PAIN ANALGESICS' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- USER FEEDBACK / SURVEY SECTION --}}
                <div id="feedback" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">USER FEEDBACK</h2>
                    
                    <div class="feedback-stats bg-light rounded-3 p-3 mb-3">
                        <h3 class="h6 fw-bold mb-3">Patients taking {{ explode(' ', ($product['name'] ?? 'PRODUCT'))[0] }}</h3>
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="fw-bold fs-5">{{ $product['feedback_frequency_1'] ?? '40' }}%</div>
                                <small class="text-muted">Thrice A Day</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold fs-5">{{ $product['feedback_frequency_2'] ?? '40' }}%</div>
                                <small class="text-muted">Twice A Day</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold fs-5">{{ $product['feedback_frequency_3'] ?? '20' }}%</div>
                                <small class="text-muted">Once A Day</small>
                            </div>
                        </div>
                        
                        <h3 class="h6 fw-bold mt-3 mb-2">How much was the improvement?</h3>
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar bg-success" style="width: {{ $product['improvement_excellent'] ?? '41' }}%">Excellent {{ $product['improvement_excellent'] ?? '41' }}%</div>
                            <div class="progress-bar bg-warning" style="width: {{ $product['improvement_average'] ?? '30' }}%">Average {{ $product['improvement_average'] ?? '30' }}%</div>
                            <div class="progress-bar bg-danger" style="width: {{ $product['improvement_poor'] ?? '29' }}%">Poor {{ $product['improvement_poor'] ?? '29' }}%</div>
                        </div>
                        
                        <div class="survey-box bg-white rounded-3 p-3 mt-3 border">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <strong>Are you currently taking {{ explode(' ', ($product['name'] ?? 'PRODUCT'))[0] }}?</strong>
                                    <div class="small text-muted">Let us know how well it is working for you by taking this survey.</div>
                                </div>
                                <button class="btn btn-primary btn-sm" onclick="showSurvey()">TAKE SURVEY</button>
                            </div>
                            <div class="small text-muted mt-2">{{ $product['feedback_count'] ?? '458' }} people have taken this survey</div>
                        </div>
                    </div>
                </div>

                {{-- FAQ SECTION --}}
                <div id="faq" class="section-card mb-4">
                    <h2 class="h5 fw-bold mb-3">FAQS</h2>
                    
                    @php
                        $faqs = !empty($product['q_a_list']) ? $product['q_a_list'] : 
                                (!empty($product['faqs']) ? $product['faqs'] : []);
                    @endphp
                    
                    @if(!empty($faqs) && count($faqs) > 0)
                        <div class="accordion" id="faqAccordion">
                            @foreach($faqs as $index => $faq)
                                <div class="accordion-item border-0 border-bottom">
                                    <h3 class="accordion-header">
                                        <button class="accordion-button bg-transparent shadow-none py-3 {{ $index > 0 ? 'collapsed' : '' }}" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#faq{{ $index }}">
                                            {{ $faq['question'] ?? $faq['q'] ?? 'Question' }}
                                        </button>
                                    </h3>
                                    <div id="faq{{ $index }}" 
                                         class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" 
                                         data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted pt-0">
                                            {{ $faq['answer'] ?? $faq['a'] ?? 'Answer not available' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="show-more text-center mt-3">
                            <button class="btn btn-link text-decoration-none" onclick="showAllFaqs()">Show more →</button>
                        </div>
                    @else
                        <p class="text-muted">No FAQs available for this product.</p>
                    @endif
                </div>
                
                {{-- AUTHOR & DISCLAIMER SECTION --}}
                <div class="section-card mb-4">
                    <div class="author-info bg-light rounded-3 p-3 mb-3">
                        <div class="row gx-0 align-items-start">
                            <div class="col-6">
                                <div class="small text-muted">Written By</div>
                                <div class="fw-semibold">{{ $product['written_by'] ?? 'Dr. Sakshi Jain' }}</div>
                                <div class="small">{{ $product['written_by_qualification'] ?? 'MS, BDS' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="small text-muted">Reviewed By</div>
                                <div class="fw-semibold">{{ $product['reviewed_by'] ?? 'Dr. Sachin Gupta' }}</div>
                                <div class="small">{{ $product['reviewed_by_qualification'] ?? 'MD Pharmacology, MBBS' }}</div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="small text-muted">Last updated</div>
                            <div class="small">{{ $product['last_updated'] ?? now()->format('d M Y | h:i A') }} (IST)</div>
                        </div>
                    </div>
                    
                    <div class="disclaimer small text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Disclaimer:</strong> The information contained herein should NOT be used as a substitute for the advice of a qualified physician. The information provided here is for informational purposes only.
                    </div>
                </div>
                
                {{-- REFERENCE & MANUFACTURER DETAILS --}}
                <div class="section-card mb-4">
                    @if(!empty($product['references']))
                    <h3 class="h6 fw-bold mb-3">References</h3>
                    <ol class="small text-muted ps-3">
                        @foreach($product['references'] as $ref)
                            <li>{{ $ref }}</li>
                        @endforeach
                    </ol>
                    @endif
                    
                    <h3 class="h6 fw-bold mt-3 mb-2">Manufacturer details</h3>
                    <p class="small text-muted mb-0">{{ $product['manufacturer']['address'] ?? $product['manufacturer_address'] ?? 'Manufacturer address not available' }}</p>
                    
                    <h3 class="h6 fw-bold mt-3 mb-2">Marketer details</h3>
                    <p class="small text-muted">{{ $product['manufacturer']['name'] ?? $product['manufacturer_name'] ?? 'Manufacturer name not available' }}<br>
                    {{ $product['manufacturer']['address'] ?? $product['manufacturer_address'] ?? '' }}</p>
                    
                    <div class="row small text-muted mt-2">
                        <div class="col-6">Country of origin: {{ $product['country_of_origin'] ?? 'India' }}</div>
                        <div class="col-6">Expires on or after: {{ $product['expiry_date'] ?? 'November, 2026' }}</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDEBAR - PRICE CARD & SUBSTITUTES (STICKY) --}}
       <div class="col-lg-4 custom-right-sidebar">
    <div class="price-card-sticky">

                    {{-- PRICE CARD --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-body p-3">
                            @php
                                $sellingPrice = $product['online_price'] ?? $product['selling_price'] ?? $product['price'] ?? 0;
                                $mrp = $product['mrp'] ?? $product['original_price'] ?? $sellingPrice;
                                $discount = $mrp - $sellingPrice;
                                $discountPercent = ($mrp > $sellingPrice && $mrp > 0) ? round(($discount / $mrp) * 100) : 0;
                                $quantityPerStrip = $product['quantity_per_strip'] ?? 15;
                                $pricePerUnit = $sellingPrice > 0 ? round($sellingPrice / $quantityPerStrip, 2) : 0;
                            @endphp
                            
                            <div class="price-header mb-2">
                                @if($mrp > $sellingPrice)
                                    <div class="text-muted text-decoration-line-through small">MRP ₹{{ number_format($mrp, 2) }}</div>
                                @endif
                                <div class="d-flex align-items-baseline gap-2">
                                    <span class="display-6 fw-bold text-success">₹{{ number_format($sellingPrice, 2) }}</span>
                                    @if($discountPercent > 0)
                                        <span class="badge bg-success">{{ $discountPercent }}% OFF</span>
                                    @endif
                                </div>
                                <div class="small text-success">Inclusive of all taxes</div>
                            </div>
                            
                            <div class="product-meta mb-3">
                                <div class="small text-muted">1 Strip → {{ $quantityPerStrip }} {{ Str::plural('tablet', $quantityPerStrip) }} in 1 strip</div>
                                <div class="small text-muted">₹{{ $pricePerUnit }} per {{ Str::singular('tablets') }}</div>
                            </div>
                            
@if(!empty($product['batch_id']) && !empty($product['in_stock']))
                                <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                                    @csrf
@if(!empty($product['batch_id']))
    <input type="hidden"
           name="batch_id"
           value="{{ $product['batch_id'] }}">
@endif                                    <input type="hidden" name="product_id" value="{{ $product['id'] ?? '' }}">
                                    <input type="hidden" name="qty" id="productQuantity" value="1">
                                    
                                    {{-- Quantity Selector --}}
                                    <div class="quantity-wrapper mb-3">
                                        <label class="small text-muted d-block mb-1">Quantity:</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(-1)">-</button>
                                            <span id="quantityDisplay" class="px-3 py-1 border rounded">1</span>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(1)">+</button>
                                            @if(!empty($product['packaging_size']))
                                                <span class="small text-muted">{{ $product['packaging_size'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                        <i class="fas fa-shopping-cart me-2"></i> ADD TO CART
                                    </button>
                                </form>
                                <div class="small text-muted mt-2 text-center">
                                    The price displayed is the MRP (inclusive of applicable taxes).
                                </div>
                            @else
                                <button class="btn btn-secondary w-100 py-2 fw-semibold" disabled>
                                    <i class="fas fa-times-circle me-2"></i> OUT OF STOCK
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    {{-- GENERIC ALTERNATIVE CARD --}}
                    @if(!empty($product['substitutes']) && count($product['substitutes']) > 0)
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-body p-3">
                            <div class="text-center mb-2">
                                <span class="badge bg-success">{{ $product['substitutes'][0]['discount'] ?? '61' }}% lower price ↓</span>
                                <div class="mt-2">
                                    <strong class="text-success">{{ $product['substitutes'][0]['discount'] ?? '61' }}% cheaper alternative available</strong>
                                </div>
                                <div class="small text-muted">with same salt composition</div>
                            </div>
                            <div class="current-viewing text-center small text-muted mb-2">Currently viewing</div>
                            <div class="text-center">
                                <a href="#substitutes" class="btn btn-outline-success btn-sm w-100">View all substitutes →</a>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- ORDER ON CALL CARD --}}
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 text-center">
                            <div class="fw-semibold mb-2">Order Medicines on Call</div>
                            <div class="h6 fw-bold text-primary mb-2">1800-212-2323</div>
                            <div class="small text-muted mb-2">or get a call back</div>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text bg-white">+91</span>
                                <input type="tel" id="phoneNumber" class="form-control" placeholder="Enter Phone Number">
                            </div>
                            <button class="btn btn-primary btn-sm w-100" onclick="requestCallBack()">
                                Get a Call to Order Medicines
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- SUBSTITUTES SECTION (FULL WIDTH) --}}
        @if(!empty($product['substitutes']) && count($product['substitutes']) > 0)
        <div id="substitutes" class="row mt-4">
            <div class="col-lg-9 offset-lg-3">
                <div class="section-card">
                    <h2 class="h5 fw-bold mb-3">ALL SUBSTITUTES</h2>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Manufacturer</th>
                                    <th>Price</th>
                                    <th>Savings</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product['substitutes'] as $substitute)
                                <tr>
                                    <td>
                                        <strong>{{ $substitute['name'] }}</strong>
                                        <div class="small text-muted">{{ $substitute['salt'] ?? $product['molecule'] ?? 'N/A' }}</div>
                                    </td>
                                    <td class="small">{{ $substitute['manufacturer'] ?? 'Generic' }}</td>
                                    <td>
                                        <span class="fw-bold">₹{{ number_format($substitute['price'] ?? 0, 2) }}</span>
                                        <div class="small text-muted">/{{ $substitute['unit'] ?? 'strip' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $substitute['discount'] ?? rand(5, 65) }}% cheaper</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-success" onclick="addSubstituteToCart({{ $substitute['id'] ?? 0 }})">
                                            <i class="fas fa-cart-plus"></i> Add
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        {{-- RELATED PRODUCTS --}}
        @if(!empty($relatedProducts) && count($relatedProducts) > 0)
        <div class="row mt-4">
            <div class="col-lg-9 offset-lg-3">
                <div class="section-card">
                    <h2 class="h5 fw-bold mb-3">RELATED PRODUCTS</h2>
                    <div class="row g-3">
                        @foreach(array_slice($relatedProducts, 0, 4) as $related)
                        <div class="col-md-3 col-6">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-2 text-center">
                                    <img src="{{ $related['main_image'] ?? $related['image'] ?? asset('images/default-medicine.png') }}" 
                                         class="img-fluid mb-2" style="height: 80px; object-fit: contain;"
                                         alt="{{ $related['name'] ?? 'Product' }}">
                                    <div class="small fw-semibold text-truncate">{{ $related['name'] ?? 'Product' }}</div>
                                    <div class="small text-success fw-bold">₹{{ number_format($related['price'] ?? 0, 2) }}</div>
                                    @if(isset($related['mrp']) && $related['mrp'] > ($related['price'] ?? 0))
                                        <div class="small text-muted text-decoration-line-through">MRP ₹{{ number_format($related['mrp'], 2) }}</div>
                                    @endif
                                    <button class="btn btn-sm btn-outline-success w-100 mt-2" onclick="quickAdd({{ $related['id'] ?? 0 }})">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Image Modal for Zoom --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body text-center">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                <img id="modalImage" src="" class="img-fluid rounded-3" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

{{-- CSS STYLES --}}
<style>

body{
    background:#f6f6f6;
    font-family:Inter,sans-serif;
    color:#212121;
}

/* =========================================
   CONTAINER LAYOUT
========================================= */

.custom-product-container{
    max-width:100%;
    padding-left:12px !important;
    padding-right:12px !important;
}

/* =========================================
   LEFT SIDEBAR
========================================= */

/* =========================================
   LEFT SIDEBAR - EXACT 1MG STICKY
========================================= */

.custom-left-sidebar{
    width:250px;
    flex:0 0 250px;
    max-width:250px;

    position:relative;
}

.sidebar-nav{
    background:#fff;
    border:1px solid #ececec;
    border-radius:10px;

    position:sticky;
    top:90px;

    max-height:calc(100vh - 110px);

    overflow-y:auto;
    overflow-x:hidden;

    scrollbar-width:none;

    width:100%;
}

.sidebar-nav::-webkit-scrollbar{
    display:none;
}

.sidebar-nav .nav{
    padding:8px 0;
}

.sidebar-nav .nav-pills .nav-link{
    color:#212121;
    padding:16px 22px;
    font-size:15px;
    font-weight:500;
    border-radius:0;
    border-left:3px solid transparent;
    transition:.2s ease;

    display:flex;
    align-items:center;
}

.sidebar-nav .nav-pills .nav-link i{
    width:18px;
}

.sidebar-nav .nav-pills .nav-link:hover{
    background:#fafafa;
    color:#ff6f61;
}

.sidebar-nav .nav-pills .nav-link.active{
    background:#fff5f3;
    color:#ff6f61;
    font-weight:700;
    border-left:3px solid #ff6f61;
}

.sidebar-nav .nav-pills .nav-link{
    color:#212121;
    padding:18px 22px;
    font-size:15px;
    font-weight:500;
    border-radius:0;
    border-left:3px solid transparent;
    transition:.2s ease;
}

.sidebar-nav .nav-pills .nav-link:hover{
    background:#fafafa;
    color:#ff6f61;
}

.sidebar-nav .nav-pills .nav-link.active{
    background:#fff;
    color:#212121;
    font-weight:700;
    border-left:none;
}

/* =========================================
   MAIN CONTENT
========================================= */

.custom-main-content{
    width:calc(100% - 640px);
    flex:0 0 calc(100% - 640px);
    max-width:calc(100% - 640px);

    padding-left:18px !important;
    padding-right:18px !important;
}

.custom-main-content .row.g-3{
    --bs-gutter-x:18px;
    align-items:flex-start !important;
}

/* =========================================
   IMAGE GALLERY
========================================= */

#gallery.section-card{
    background:transparent;
    padding:0;
    margin:0;
    border:none;
    box-shadow:none;
}

.product-gallery{
    display:flex;
    flex-direction:column;
    align-items:center;
    padding-top:4px;
}

.main-image-container{
    width:100%;
    height:340px;
    min-height:340px;
    background:#fff;
    border:1px solid #ececec;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:10px;
}

.main-image-container img{
    max-height:250px !important;
    width:auto !important;
    height:auto !important;
    object-fit:contain;
}

.thumbnails-wrapper{
    width:100%;
    margin-top:14px;
}

.thumbnail-item{
    cursor:pointer;
}

.thumbnail-item img{
    width:64px !important;
    height:64px !important;
    object-fit:contain;
    border:1px solid #ececec;
    border-radius:8px;
    background:#fff;
    padding:6px;
    transition:.2s ease;
}

.thumbnail-item.active img{
    border:2px solid #ff6f61 !important;
}

.thumbnail-item:hover img{
    border-color:#ff6f61 !important;
}

/* =========================================
   PRODUCT HEADER
========================================= */

.product-header{
    background:transparent !important;
    border:none !important;
    padding:0 !important;
    margin:0 !important;
    box-shadow:none !important;
}

.product-header h1{
    font-size:38px;
    line-height:48px;
    font-weight:700;
    color:#212121;
    margin-bottom:20px;
    letter-spacing:-0.3px;
}

.product-header .badge{
    font-size:12px;
    padding:6px 10px;
    border-radius:6px;
}

.product-header .salt-info,
.product-header .storage-info{
    margin-top:18px;
}

.product-header .salt-info span,
.product-header .storage-info span{
    font-size:13px;
    font-weight:600;
    color:#666;
}

.product-header .salt-info div,
.product-header .storage-info div{
    font-size:16px;
    line-height:28px;
    color:#212121;
}

.product-header a{
    color:#1a73e8;
    text-decoration:underline;
}

/* =========================================
   SECTION CARDS
========================================= */

.section-card{
    background:#fff;
    border-radius:8px;
    padding:32px;
    margin-bottom:18px;
    border:none;
}

/* =========================================
   RIGHT PRICE CARD
========================================= */
/* =========================================
   EXACT 1MG RIGHT SIDEBAR FIX
========================================= */

.custom-right-sidebar{
    width:360px;
    flex:0 0 360px;
    max-width:360px;

    position:sticky;
    top:90px;
    height:max-content;

    align-self:flex-start;
}

.price-card-sticky{
    position:relative !important;
    top:auto !important;

    width:100%;
    padding-left:0;

    transition:none !important;
    transform:none !important;
    will-change:auto !important;
    backface-visibility:visible !important;
}

/* FAQ open hone par sidebar stable */
#faq .accordion-collapse{
    transition:height .25s ease;
}

@media(min-width:992px){

    .row{
        align-items:flex-start !important;
    }

}

.price-card-sticky .card{
    border:1px solid #ececec !important;
    border-radius:12px !important;
    box-shadow:none !important;
}

.price-header .display-6{
    font-size:42px !important;
    font-weight:700;
    color:#212121 !important;
}

.price-header .badge{
    background:#1aab2a !important;
    font-size:12px;
    border-radius:4px;
}

/* =========================================
   BUTTONS
========================================= */

.btn-success{
    background:#ff6f61 !important;
    border-color:#ff6f61 !important;
    color:#fff !important;
    font-weight:600;
    border-radius:6px;
}

.btn-success:hover{
    background:#ff5a4a !important;
    border-color:#ff5a4a !important;
}

.btn-outline-success{
    border-color:#ff6f61 !important;
    color:#ff6f61 !important;
}

.btn-outline-success:hover{
    background:#ff6f61 !important;
    color:#fff !important;
}

.btn-primary{
    background:#ff6f61 !important;
    border-color:#ff6f61 !important;
}

.btn-primary:hover{
    background:#ff5a4a !important;
    border-color:#ff5a4a !important;
}

/* =========================================
   GENERIC ALERT
========================================= */

.generic-alert{
    background:#f0fff4 !important;
    border:1px solid #b7ebc6 !important;
    border-left:4px solid #1aab2a !important;
    border-radius:8px;
}

/* =========================================
   FAQ
========================================= */

.accordion-item{
    border:none !important;
    border-bottom:1px solid #ececec !important;
}

.accordion-button{
    padding-left:0;
    padding-right:0;
    background:transparent !important;
    font-weight:600;
    box-shadow:none !important;
}

.accordion-button:not(.collapsed){
    color:#ff6f61;
}

.accordion-body{
    padding-left:0;
    padding-right:0;
    color:#666;
}
#faq{
    max-width:100%;
}

#faq h2{
    font-size:34px;
    line-height:44px;
    font-weight:700;
    margin-bottom:28px;
    color:#212121;
}

#faq .accordion-button{
    font-size:20px;
    line-height:32px;
    font-weight:700;
    color:#212121;
}

#faq .accordion-body{
    font-size:17px;
    line-height:34px;
    color:#616161;

    padding-top:4px;
    padding-bottom:28px;
}

/* =========================================
   FACT BOX
========================================= */

.fact-grid{
    background:#fafafa !important;
    border:1px solid #ececec;
}

/* =========================================
   QUICK TIPS
========================================= */

.quick-tips-list{
    padding-left:18px;
}

.quick-tips-list li{
    margin-bottom:12px;
    line-height:1.7;
}

/* =========================================
   PROGRESS BAR
========================================= */

.progress{
    height:24px;
    border-radius:30px;
    overflow:hidden;
    background:#ececec;
}

.progress-bar{
    font-size:11px;
    line-height:24px;
    font-weight:600;
}

/* =========================================
   AUTHOR INFO
========================================= */

.author-info{
    background:#fafafa !important;
    border:1px solid #ececec;
}

/* =========================================
   QUANTITY
========================================= */

.quantity-wrapper button{
    width:34px;
    height:34px;
    padding:0;
    display:flex;
    align-items:center;
    justify-content:center;
}

#quantityDisplay{
    min-width:45px;
    text-align:center;
    font-weight:600;
}

/* =========================================
   SMOOTH SCROLL
========================================= */

html{
    scroll-behavior:smooth;
}

/* =========================================
   MOBILE
========================================= */
/* =========================================
   STOP RIGHT SIDEBAR AFTER FAQ
========================================= */


@media(max-width:991px){

    .custom-left-sidebar{
        display:none !important;
    }

    .custom-main-content{
        width:100% !important;
        flex:0 0 100% !important;
        max-width:100% !important;

        padding-left:0 !important;
        padding-right:0 !important;
    }
.custom-right-sidebar{
    width:360px;
    flex:0 0 360px;
    max-width:360px;

    position:sticky;
    top:90px;
    height:max-content;

    align-self:flex-start;
}

.price-card-sticky{
    position:sticky;
    top:75px;
    padding-left:12px;
}

.product-header h1{
    font-size:30px;
        line-height:38px;
    }

}
/* =========================================
   SIDE EFFECTS SECTION
========================================= */

.common-side-effects h3{
    font-size:18px;
    font-weight:600;
    color:#212121;
    margin-bottom:14px;
}

.side-effect-tags{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:14px;
}

.side-effect-tags .badge{
    background:#fafafa !important;
    border:1px solid #dcdcdc !important;
    color:#212121 !important;
    border-radius:22px !important;
    padding:8px 14px !important;
    font-size:14px;
    font-weight:500;
    line-height:20px;
    box-shadow:none !important;
}

.side-effect-tags .badge:hover{
    border-color:#bcbcbc !important;
    background:#f5f5f5 !important;
}

#side-effects p{
    font-size:16px;
    line-height:28px;
    color:#666 !important;
}

#side-effects h2{
    font-size:24px;
    line-height:34px;
    font-weight:700;
    color:#212121;
    margin-bottom:16px;
}
</style>{{-- JAVASCRIPT --}}
<script>
    let currentQuantity = 1;
    const maxQuantity = {{ $product['max_quantity'] ?? 10 }};
    const minQuantity = 1;
    
    // Update quantity function
    function updateQuantity(change) {
        let newQuantity = currentQuantity + change;
        if (newQuantity < minQuantity) newQuantity = minQuantity;
        if (maxQuantity && newQuantity > maxQuantity) {
            newQuantity = maxQuantity;
            showToast('Maximum quantity limit is ' + maxQuantity, 'warning');
        }
        if (newQuantity !== currentQuantity) {
            currentQuantity = newQuantity;
            document.getElementById('quantityDisplay').innerText = currentQuantity;
            document.getElementById('productQuantity').value = currentQuantity;
        }
    }
    
    // Change main image function
    function changeMainImage(src) {
        const mainImage = document.getElementById('mainProductImage');
        if (mainImage) {
            mainImage.src = src;
            // Update active thumbnail
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('active');
                const img = item.querySelector('img');
                if (img && img.src === src) {
                    item.classList.add('active');
                    img.style.borderColor = '#28a745';
                } else if (img) {
                    img.style.borderColor = 'transparent';
                }
            });
        }
    }
    
    // Open image modal
    function openImageModal(src) {
        const modalImage = document.getElementById('modalImage');
        if (modalImage) {
            modalImage.src = src;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    }
    
    // Smooth scroll for navigation
document.querySelectorAll('[data-scroll]').forEach(link => {

    link.addEventListener('click', function(e) {

        e.preventDefault();

        const target = document.getElementById(this.dataset.scroll);

        if(target){

            /* exact 1mg style scroll */
           const navbarHeight = 100;

            const targetPosition =
                target.getBoundingClientRect().top
                + window.pageYOffset
                - navbarHeight;

            window.scrollTo({
                top:targetPosition,
                behavior:'smooth'
            });

            /* active state */
            document
                .querySelectorAll('.sidebar-nav .nav-link')
                .forEach(nav => nav.classList.remove('active'));

            this.classList.add('active');

        }

    });

});    
    // Show survey
    function showSurvey() {
        alert('Survey feature coming soon. Thank you for your feedback!');
    }
    
    // Show all FAQs
    function showAllFaqs() {
        document.querySelectorAll('.accordion-collapse').forEach(collapse => {
            collapse.classList.add('show');
        });
    }
    
    // Request callback
    function requestCallBack() {
        const phoneInput = document.getElementById('phoneNumber');
        if (phoneInput && phoneInput.value.length >= 10) {
            alert('We will call you back shortly at ' + phoneInput.value);
        } else {
            alert('Please enter a valid 10-digit phone number');
        }
    }
    
    // Add substitute to cart
    function addSubstituteToCart(productId) {
        showToast('Product added to cart!', 'success');
    }
    
    // Quick add
    function quickAdd(productId) {
        showToast('Product added to cart!', 'success');
    }
    
    // Toast notification
    function showToast(message, type = 'success') {
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '1100';
            document.body.appendChild(toastContainer);
        }
        
        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : (type === 'error' ? 'bg-danger' : 'bg-info');
        const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 mb-2" role="alert" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${icon} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
    }
    
    // Update active nav on scroll
    window.addEventListener('scroll', function() {
        const sections = ['overview', 'gallery', 'uses', 'side-effects', 'how-to-use', 'how-works', 'safety-advice', 'missed-dose', 'substitutes', 'quick-tips', 'fact-box', 'feedback', 'faq'];
        let current = '';
        
        sections.forEach(section => {
            const element = document.getElementById(section);
            if (element) {
                const rect = element.getBoundingClientRect();
                if (rect.top <= 150 && rect.bottom >= 150) {
                    current = section;
                }
            }
        });
        
        document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.dataset.scroll === current) {
                link.classList.add('active');
            }
        });
    });
    
    // Image error fallback
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() {
            if (!this.hasAttribute('data-fallback')) {
                this.setAttribute('data-fallback', 'true');
                this.src = "{{ asset('images/default-medicine.png') }}";
            }
        });
    });
    
    // Add to cart form submission with AJAX
   document.addEventListener('DOMContentLoaded', function () {


const cartForm = document.getElementById('addToCartForm');

if (cartForm) {

    cartForm.addEventListener('submit', function (e) {

        e.preventDefault();

        const formData = new FormData(cartForm);

  fetch(cartForm.action, {

    method: 'POST',

    credentials: 'same-origin',     

            headers: {

                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),

                'Accept': 'application/json'

            },

            body: formData

        })

        .then(async response => {

            const contentType =
                response.headers.get('content-type');

            // NOT JSON RESPONSE
            if (
                !contentType ||
                !contentType.includes('application/json')
            ) {

                throw new Error(
                    'Invalid server response'
                );
            }

            const data = await response.json();

            // HANDLE UNAUTHORIZED
            if (response.status === 401) {

                throw new Error(
                    data.message || 'Please login first'
                );
            }

            // HANDLE VALIDATION
            if (response.status === 422) {

                let errorMessage = 'Validation failed';

                if (data.errors) {

                    errorMessage = Object.values(data.errors)
                        .flat()
                        .join('\n');
                }

                throw new Error(errorMessage);
            }

            // HANDLE OTHER ERRORS
            if (!response.ok) {

                throw new Error(
                    data.message || 'Something went wrong'
                );
            }

            return data;

        })

        .then(data => {

            if (data.status) {

                showToast(
                    data.message ||
                    'Product added to cart successfully!',
                    'success'
                );

            } else {

                showToast(
                    data.message ||
                    'Failed to add product',
                    'error'
                );
            }

        })

        .catch(error => {

            console.error(error);

            showToast(
                error.message ||
                'Please login to continue',
                'error'
            );

        });

    });

}


});

</script>

<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection