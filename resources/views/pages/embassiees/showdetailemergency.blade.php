@extends('layouts.master')

@section('title','More Details')
@section('page-title', 'East Timor Airports')

@push('styles')

<style>
    #map {
        height: 600px;
    }

    table {
        border: 1px solid black;
        border-collapse: collapse;
    }
    td {
        border: 1px solid black;
        padding: 4px;
    }

     p{
        margin-bottom: 8px;
        line-height: 18px;
    }

     .btn-danger{
        background-color:#395272;
        border-color: transparent;
    }

     .btn-danger:hover{
        background-color:#5686c3;
        border-color: transparent;
    }

     .btn.active {
        background-color: #5686c3 !important;
        border-color: transparent !important;
        color: #fff !important;
    }

    .p-3{
        padding: 10px !important;
        margin: 0 3px;
    }

    .btn-outline-danger{
        color: #FFFFFF;
        background-color:#395272;
        border-color: transparent;
    }

    .btn-outline-danger:hover{
        background-color:#5686c3;
        border-color: transparent;
    }

    .fa,
    .fab,
    .fad,
    .fal,
    .far,
    .fas {
        color: #346abb;
    }

    .card-header{
        padding: 0.25rem 1.25rem;
        color: #3c66b5;
        font-weight: bold;
    }

    .mb-4{
        margin-bottom: 0.5rem !important;
    }

     /* Classification section */
    .classification {
      display: flex;
      width: 100%;
    }
    .emergency-tools-card { container-type: inline-size; }
    .emergency-legend {
      display: grid;
      grid-template-columns: 30% minmax(0, 1fr) 22%;
      gap: 16px;
      align-items: start;
      padding: 6px 12px 10px;
    }
    .emergency-legend > * { min-width: 0; }
    .emergency-legend .class-header,
    .emergency-legend .class-column { text-align: left; }
    .emergency-legend .hospital-list { align-items: flex-start; }
    .emergency-legend .hospital-row { justify-content: flex-start; }
    .medical-legend .hospital-item .btn {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 6px;
      width: auto;
      padding-left: 0 !important;
      text-align: left;
    }
    .police-legend { min-width: 0; }
    .police-legend .legend-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .police-legend .legend-grid-item {
      white-space: normal;
      align-items: flex-start;
      line-height: 1.2;
    }
    .police-legend .legend-grid-item small { overflow-wrap: anywhere; }
    @container (max-width: 900px) {
      .emergency-legend { grid-template-columns: 1fr; }
    }

    .class-column {
      flex: 1;
      text-align: center;

    }
    .class-column:last-child {
      border-right: none;
    }

    .class-header {
      font-weight: 600;
      padding: 0.1rem 0;
    }

    /* Color bars */
    .class-medical-classification {border: none; text-align: center;}
    .class-airport-category {border: none;}
    .class-advanced { border-bottom: 3px solid #0070c0; }
    .class-intermediate { border-bottom: 3px solid #00b050; }
    .class-basic { border-bottom: 3px solid #ffc000; }

    /* Hospital layout */
    .hospital-list {
      display: flex;
      flex-direction: column;
      align-items: center;

    }

    /* For side-by-side classes */
    .hospital-row {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0;
    }

    .hospital-item {
      display: flex;
      align-items: center;
      gap: 0;
      font-size: 0.9rem;
      white-space: nowrap;
    }

    .hospital-icon {
      width: 18px;
      height: 18px;
      border-radius: 3px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    /* Image inside icon box */
    .hospital-icon img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    /* Airfield icons */
    .category-item img {
      width: 16px;
      height: 16px;
      object-fit: contain;
    }

    .legend-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        width: 100%;
        align-items: start;
    }

    .legend-grid-item {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        width: 100%;
        text-align: left;
        white-space: nowrap;
    }

    .legend-grid-item img {
        flex-shrink: 0;
    }

    .legend-grid-item small {
        text-align: left;
    }

    /* ====== DIRECTIONS PANEL - Modern Styling ====== */
    #directionsPanel {
        font-family: 'Segoe UI', Roboto, -apple-system, sans-serif !important;
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 transparent;
    }
    #directionsPanel::-webkit-scrollbar { width: 5px; }
    #directionsPanel::-webkit-scrollbar-thumb {
        background: #c1c1c1; border-radius: 10px;
    }
    #directionsPanel .dp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: linear-gradient(135deg, #1a73e8, #4285f4);
        border-radius: 8px 8px 0 0;
        margin: 0;
        color: #fff;
    }
    #directionsPanel .dp-header-title {
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #directionsPanel .dp-header-title i { color: #fff !important; font-size: 16px; }
    #directionsPanel .dp-close-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        width: 28px; height: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: background 0.2s;
    }
    #directionsPanel .dp-close-btn:hover { background: rgba(255,255,255,0.35); }
    #directionsPanel .dp-close-btn i { color: #fff !important; }

    /* Google-generated table overrides */
    #directionsPanel table { border: none !important; width: 100%; }
    #directionsPanel td {
        border: none !important;
        padding: 6px 4px !important;
        font-size: 13px;
        vertical-align: top;
    }
    #directionsPanel .adp-directions { margin: 0 !important; }

    /* Route summary (origin → destination bar) */
    #directionsPanel .adp-placemark {
        background: #f0f4ff;
        border-radius: 8px;
        margin-bottom: 8px !important;
        overflow: hidden;
    }
    #directionsPanel .adp-placemark td {
        padding: 10px 12px !important;
        font-weight: 600;
        color: #1a3c6e;
        font-size: 13px;
    }
    #directionsPanel .adp-placemark img {
        filter: hue-rotate(200deg) saturate(1.5);
    }

    /* Summary bar (distance & time) */
    #directionsPanel .adp-summary {
        background: linear-gradient(135deg, #e8f0fe, #d2e3fc);
        border-radius: 8px;
        padding: 10px 14px !important;
        margin: 8px 0 !important;
        font-size: 13px;
        color: #1a3c6e;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Step list */
    #directionsPanel .adp-listsel,
    #directionsPanel .adp-list {
        border: none !important;
    }
    #directionsPanel .adp-listinfo {
        border: none !important;
        background: transparent !important;
    }

    /* Individual step rows */
    #directionsPanel .adp-step {
        border-bottom: 1px solid #eef1f5 !important;
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        transition: background 0.15s;
        border-radius: 6px;
        margin-bottom: 2px;
    }
    #directionsPanel .adp-step:hover {
        background: #f5f8ff !important;
    }
    #directionsPanel .adp-step:last-child {
        border-bottom: none !important;
    }

    /* Step icon cell */
    #directionsPanel .adp-step .adp-stepicon {
        padding: 8px 4px 8px 8px !important;
    }
    #directionsPanel .adp-step .adp-stepicon .adp-maneuver {
        width: 20px;
        height: 20px;
    }

    /* Step text */
    #directionsPanel .adp-step .adp-substep {
        padding: 8px 12px 8px 4px !important;
        color: #333;
        line-height: 1.5;
        font-size: 12.5px;
    }
    #directionsPanel .adp-step .adp-substep b {
        color: #1a73e8;
        font-weight: 600;
    }
    /* Step distance */
    #directionsPanel .adp-step td:last-child {
        color: #5f6368;
        font-size: 12px;
        white-space: nowrap;
        padding-right: 10px !important;
    }

    /* Warning / legal */
    #directionsPanel .adp-warnbox,
    #directionsPanel .adp-legal {
        font-size: 11px;
        color: #888;
        padding: 6px 12px !important;
        border: none !important;
    }
    #directionsPanel .adp-legal a { color: #1a73e8; }

    /* Highlighted / selected step */
    #directionsPanel .adp-listsel {
        background: #e8f0fe !important;
        border-radius: 6px;
    }

    /* === Info modal bertab (Polda / Polres / Polsek) ===
       Sama seperti di halaman Police, Dashboard, Airports, & Hospital. Lebarnya
       cukup untuk satu baris tab, tingginya mengikuti isi. CSS halaman ini
       Bootstrap 4 (AdminLTE), jadi lebar dialog harus di-override sendiri. */
    .info-modal-dialog {
        max-width: 1180px;
        width: 95vw;
    }
    .info-modal-dialog .modal-content {
        max-height: 88vh;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .info-modal-dialog .modal-header {
        flex: 0 0 auto;
        background: #f8f9fa;
    }

    .info-modal-tabs {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        flex: 0 0 auto;
        flex-wrap: nowrap;
        gap: 2px;
        overflow-x: auto;
    }
    .info-modal-tabs .nav-link {
        border: 1px solid transparent;
        border-bottom: none;
        border-radius: 6px 6px 0 0;
        color: #55606e;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 14px;
        white-space: nowrap;
    }
    .info-modal-tabs .nav-link:hover {
        background: #eef2f7;
        color: #395272;
    }
    .info-modal-tabs .nav-link.active {
        background: #fff;
        color: #395272;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    .info-modal-body {
        padding: 0;
        overflow: hidden;
        flex: 1 1 auto;
        min-height: 0;
    }
    .info-modal-content {
        overflow-y: auto;
        padding: 18px 24px 24px 24px;
        min-height: 260px;
        max-height: calc(88vh - 120px);
    }
    .info-modal-content ul {
        padding-left: 20px;
        margin-bottom: 12px;
    }
    .info-modal-content ul li {
        margin-bottom: 6px;
        line-height: 20px;
        text-align: justify;
    }
    .info-modal-content ul ul {
        margin-top: 6px;
        margin-bottom: 4px;
        list-style-type: circle;
        padding-left: 20px;
    }
    .info-modal-content ul ul li {
        margin-bottom: 4px;
    }
    .info-modal-figure {
        margin-top: 14px;
        text-align: center;
    }
    .info-modal-figure img {
        display: inline-block;
        max-width: 100%;
        height: auto;
        border: 1px solid #e3e8ee;
        border-radius: 6px;
    }
    .info-modal-note {
        margin: 8px 0 4px 0;
        padding: 8px 12px;
        background: #f4f8fb;
        border-left: 3px solid #395272;
        border-radius: 4px;
        font-size: 12.5px;
        line-height: 19px;
        text-align: justify;
        color: #445060;
    }

    /* Tabel klasifikasi Polda (gaya biru bertingkat) */
    .polda-class-table {
        width: 100%;
        margin: 4px 0 8px 0;
        border-collapse: collapse;
        font-size: 13px;
        line-height: 19px;
        color: #10333f;
    }
    .polda-class-table th,
    .polda-class-table td {
        padding: 10px 12px;
        border: 1px solid #fff;
        text-align: justify;
        vertical-align: top;
    }
    .polda-class-table thead th {
        background: #1c7fa4;
        color: #fff;
        font-weight: 700;
        text-align: left;
        vertical-align: middle;
    }
    .polda-class-table tbody tr:nth-child(odd) td {
        background: #62c2dd;
    }
    .polda-class-table tbody tr:nth-child(even) td {
        background: #cbe7f4;
    }

    /* Tabel klasifikasi unit Polres & Polsek (kolom pertama navy, baris biru bertingkat) */
    .unit-class-table {
        width: 100%;
        margin: 4px 0 8px 0;
        border-collapse: collapse;
        font-size: 13px;
        line-height: 19px;
        color: #10333f;
    }
    .unit-class-table th,
    .unit-class-table td {
        padding: 10px 12px;
        border: 1px solid #fff;
        vertical-align: top;
    }
    .unit-class-table thead th {
        background: #14506a;
        color: #fff;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
    }
    .unit-class-table tbody th {
        background: #14506a;
        color: #fff;
        font-weight: 700;
        text-align: left;
    }
    .unit-class-table tbody tr:nth-child(odd) td {
        background: #83c9e5;
    }
    .unit-class-table tbody tr:nth-child(even) td {
        background: #cfe7f5;
    }
</style>

@endpush

@section('conten')

<div class="card">

<div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">
       <div class="d-flex flex-column gap-1">
            <h2 class="fw-bold mb-0">{{ $embassy->name_embassiees }}</h2>
        </div>

        <div class="d-flex gap-2 ms-auto">

            <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill fs-3"></i>
                <small>Home</small>
            </a>

              <!-- Button 2 -->
             <a href="{{ url('embassiees') }}/{{$embassy->id}}/detail" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees/'.$embassy->id.'/detail') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <!-- Button 5 -->
            <a href="{{ url('embassiees') }}/{{$embassy->id}}/emergency" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees/'.$embassy->id.'/emergency') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-emergency-support-white.png') }}" style="width: 24px; height: 24px;">
                <small>Emergency</small>
            </a>

            <!-- Button 6 -->
            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
            </a>

            <!-- Button 5 -->
            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Aviation</small>
            </a>

            <a href="{{ url('police') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('police') ? 'active' : '' }}">
                <i class="bi bi-person-badge" style="width: 24px; height: 24px;"></i>
                <small>Police</small>
            </a>

        </div>
</div>

   <div class="card mb-4 position-relative">
        <div class="card-body" style="padding:0 7px;">
            <small><i>Last Updated {{ $embassy->created_at->format('M Y') }}</i></small>

            @role('admin')
            <a href="{{ route('embassiees.edit', $embassy->id) }}"
            style="position:absolute; right:7px;" title="edit">
                <i class="fas fa-edit"></i>
            </a>
            @endrole
        </div>
    </div>

    <div class="row">

        <div class="col-sm-8 d-flex flex-column gap-3">
            <div class="card emergency-tools-card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-emergency-support.png') }}" style="width: 24px; height: 24px;"> Emergency Support Tools</div>

                 <div class="classification emergency-legend">
                    <!-- Airfield Classification -->
                    <div class="classification">
                      <!-- Airport -->
                      <div class="class-column">
                        <div class="class-header class-airport-category">Airfield Classification</div>
                        <div class="airport-list" style="align-items:start;">
                          <div class="hospital-row legend-grid">
                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level6Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:18px; height:18px;">
                                  <small>International</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level5Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:18px; height:18px;">
                                  <small>Domestic</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level4Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:18px; height:18px;">
                                  <small>Regional</small>
                              </button>
                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level2Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:18px; height:18px;">
                                  <small>Civil-Military</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level3Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:18px; height:18px;">
                                  <small>Military</small>
                              </button>

                              <button class="btn p-1 legend-grid-item" data-bs-toggle="modal" data-bs-target="#level1Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:18px; height:18px;">
                                  <small>Private</small>
                              </button>
                          </div>

                        </div>
                      </div>
                    </div>

                    <!-- Hospital Classification -->
                    <div class="classification medical-legend" style="flex-direction: column;">
                      <div class="class-header class-medical-classification">Medical Facility Classification</div>
                      <div class="classification">
                        <!-- Advanced -->
                        <div class="class-column">
                          <div class="class-header class-advanced">Advanced</div>
                          <div class="hospital-list">
                            <div class="hospital-item">
                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level66Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:24px; height:24px;">
                                <small>Tertiary</small>
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- Intermediate -->
                        <div class="class-column">
                          <div class="class-header class-intermediate">Intermediate</div>
                          <div class="hospital-list">
                            <div class="hospital-row">
                              <div class="hospital-item">
                                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level55Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:24px; height:24px;">
                                  <small>Secondary</small>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Basic -->
                        <div class="class-column">
                          <div class="class-header class-basic">Basic</div>
                          <div class="hospital-list">
                            <div class="hospital-row">
                              <div class="hospital-item">
                                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level44Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:24px; height:24px;">
                                  <small>Primary</small>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                     <div class="legend-section police-legend">

                        <div class="airport-list" style="align-items:start;">
                             <div class="class-header class-airport-category legend-section-title">Police Classification</div>
    <div class="hospital-row legend-grid">

                <button class="btn p-1 legend-grid-item" type="button" data-bs-toggle="modal" data-bs-target="#nationalPoliceModal">
                    <img src="{{ asset('images/Layer1.png') }}" style="width:15px; height:15px;">
                    <small>National Police (HQ)</small>
                </button>

                <button class="btn p-1 legend-grid-item" type="button" data-bs-toggle="modal" data-bs-target="#municipalPoliceModal">
                    <img src="{{ asset('images/Layer2.png') }}" style="width:15px; height:15px;">
                    <small>Municipal Police</small>
                </button>

                <button class="btn p-1 legend-grid-item" type="button" data-bs-toggle="modal" data-bs-target="#policeStationModal">
                    <img src="{{ asset('images/Layer3.png') }}" style="width:15px; height:15px;">
                    <small>Police Station</small>
                </button>

                <button class="btn p-1 legend-grid-item" type="button" data-bs-toggle="modal" data-bs-target="#localPolicePostsModal">
                    <img src="{{ asset('images/Layer4.png') }}" style="width:15px; height:15px;">
                    <small>Local Police Posts</small>
                </button>

    </div>
</div>

                    </div>
                  </div>

                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 d-flex flex-column gap-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="https://concord-consulting.com/static/img/cmt/icon/radar-icon.png" style="width: 24px; height: 24px;"> Nearest Support Facilities</div>
                <div class="card-body overflow-auto">
                    <?php echo $embassy->nearest_medical_facility; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/hotlines-icon.png') }}" style="width: 24px; height: 24px;"> Emergency Hotline</div>
                <div class="card-body">
                    <?php echo $hospital->travel_agent; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-medical-support-website.png') }}" style="width: 24px; height: 24px;"> Emergency Medical Support</div>
                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                        <?php echo $hospital->medical_support_website; ?>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="level1Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Private Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     <div class="modal-body">
        <p class="p-modal text-justify">Also known as private airfields or airstrips are primarily used for general and private aviation are owned by private individuals, groups, corporations, or organizations operated for their exclusive use that may include limited access for authorized personnel by the owner or manager. Owners are responsible to ensure safe operation, maintenance, repair, and control of who can use the facilities. Typically, they are not open to the public or provide scheduled commercial airline services and cater to private pilots, business aviation, and sometimes small charter operations. Services may be provided if authorized by the appropriate regulatory authority.</p>

        <p class="p-modal text-justify">A large majority of private airports are grass or dirt strip fields without services or facilities, they may feature amenities such as hangars, fueling facilities, maintenance services, and ground transportation options tailored to the needs of their owners or users. Private airports are not subject to the same level of regulatory oversight as public airports, but must still comply with applicable aviation regulations, safety standards, and environmental requirements. In the event of an emergency, landing at a private airport is authorized without any prior approval and should be done if landing anywhere else compromises the safety of the aircraft, crew, passengers, or cargo.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level2Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Combined (Civil-Military) Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">Also called "joint-use airport," are used by both civilian and military aircraft, where a formal agreement exists between the military and a local government agency allowing shared access to infrastructure and facilities, typically with separate passenger terminals and designated operating areas, airspace allocation, and aircraft scheduling. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level3Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Military Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     <div class="modal-body">
        <p class="p-modal text-justify">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level4Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Regional Domestic Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">A small or remote regional domestic airfield usually located in a geographically isolated area, far from major population centers, often with difficult terrain or vast distances from other airports with limited passenger traffic. May have shorter runways, basic facilities, and limited amenities, and basic infrastructure, serving primarily local communities providing access to essential services like medical transport or regional travel, rather than large-scale commercial flights.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level5Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Domestic Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     <div class="modal-body">
        <p class="p-modal text-justify">Exclusively manages flights that originate and end within the same country, does not have international customs or border control facilities. Airport often has smaller and shorter runways, suitable for smaller regional aircraft used on domestic routes, and cannot support larger haul aircraft having less developed support services. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level6Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">International Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     <div class="modal-body">
        <p class="p-modal text-justify">Meet standards set by the International Air Transport Association (IATA) and the International Civil Aviation Organization (ICAO), facilitate transnational travel managing flights between countries, have customs and border control facilities to manage passengers and cargo, and may have dedicated terminals for domestic and international flights. International airports have longer runways to accommodate larger, heavier aircraft, are often a main hub for air traffic, and can serve as a base for larger airlines. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="nationalPoliceModal" tabindex="-1" aria-labelledby="nationalPoliceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
          <img src="{{ asset('images/Layer1.png') }}" alt="" style="width:16px; height:16px; object-fit:contain; margin-right:10px;">
          <h5 class="modal-title" id="nationalPoliceModalLabel">National Police (HQ)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Command level:</strong> Top territorial police command</p>
        <p><strong>Typical Head Rank:</strong> Commander General</p>
        <p><strong>Administrative Equivalent:</strong> National level</p>
        <p class="p-modal mt-3">Represents the highest command authority of the PNTL, providing strategic leadership, national coordination, institutional governance, and overall command and control of all territorial and specialized police forces throughout Timor-Leste.</p>
        <strong>Responsibilities:</strong>
        <ul class="mb-0 mt-2">
          <li>National strategic command.</li>
          <li>Policy development.</li>
          <li>Resource allocation.</li>
          <li>Institutional oversight.</li>
          <li>National coordination.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="municipalPoliceModal" tabindex="-1" aria-labelledby="municipalPoliceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
          <img src="{{ asset('images/Layer2.png') }}" alt="" style="width:16px; height:16px; object-fit:contain; margin-right:10px;">
          <h5 class="modal-title" id="municipalPoliceModalLabel">Municipal Police</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Command level:</strong> Third-tier territorial police command</p>
        <p><strong>Typical Head Rank:</strong> Senior Superintendent or Superintendent</p>
        <p><strong>Administrative Equivalent:</strong> Municipality / Special Administrative Region</p>
        <p class="p-modal mt-3">Functions as the principal territorial police headquarters at the municipal level, responsible for directing, coordinating, and supervising all policing activities within their respective jurisdictions.</p>
        <strong>Responsibilities:</strong>
        <ul class="mb-0 mt-2">
          <li>Command police activities within municipalities.</li>
          <li>Implement national policing policies.</li>
          <li>Supervise police stations.</li>
          <li>Conduct crime-prevention operations.</li>
          <li>Manage public order incidents.</li>
          <li>Coordinate with municipal authorities.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="policeStationModal" tabindex="-1" aria-labelledby="policeStationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
          <img src="{{ asset('images/Layer3.png') }}" alt="" style="width:16px; height:16px; object-fit:contain; margin-right:10px;">
          <h5 class="modal-title" id="policeStationModalLabel">Police Station</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Command level:</strong> Fourth-tier territorial police command</p>
        <p><strong>Typical Head Rank:</strong> Inspector or Chief Inspector</p>
        <p><strong>Administrative Equivalent:</strong> Administrative Post / Urban Sector / Sub-municipal Area</p>
        <p class="p-modal mt-3">Act as the primary operational policing units responsible for delivering day-to-day law enforcement services, public safety, and community policing within designated local areas.</p>
        <strong>Responsibilities:</strong>
        <ul class="mb-0 mt-2">
          <li>Manage local police operations.</li>
          <li>Receive complaints and reports.</li>
          <li>Conduct preliminary investigations.</li>
          <li>Direct patrol operations.</li>
          <li>Implement community-policing programs.</li>
          <li>Coordinate local public-safety activities.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="localPolicePostsModal" tabindex="-1" aria-labelledby="localPolicePostsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
          <img src="{{ asset('images/Layer4.png') }}" alt="" style="width:16px; height:16px; object-fit:contain; margin-right:10px;">
          <h5 class="modal-title" id="localPolicePostsModalLabel">Local Police Posts</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Command level:</strong> Fifth-tier territorial police command</p>
        <p><strong>Typical Head Rank:</strong> Sub-Inspector or Senior Sergeant</p>
        <p><strong>Administrative Equivalent:</strong> Suco (Village) / Community</p>
        <p class="p-modal mt-3">Provide a permanent police presence at the community level by delivering frontline policing services, monitoring local security conditions, and maintaining close engagement with residents.</p>
        <strong>Responsibilities:</strong>
        <ul class="mb-0 mt-2">
          <li>Maintain police presence in communities.</li>
          <li>Monitor local security conditions.</li>
          <li>Conduct routine patrols.</li>
          <li>Support crime-prevention initiatives.</li>
          <li>Report incidents to police stations.</li>
          <li>Facilitate community engagement.</li>
        </ul>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="level44Modal" tabindex="-1" aria-labelledby="primaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
          <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" alt="" style="width:30px; height:30px;">
          <h5 class="modal-title" id="primaryModalLabel">Primary &mdash; District-Level Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="primary-tabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="primary-overview-tab" data-bs-toggle="tab" data-bs-target="#primary-overview" type="button" role="tab" aria-controls="primary-overview" aria-selected="true">Overview</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="primary-role-tab" data-bs-toggle="tab" data-bs-target="#primary-role" type="button" role="tab" aria-controls="primary-role" aria-selected="false">Role</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="primary-services-tab" data-bs-toggle="tab" data-bs-target="#primary-services" type="button" role="tab" aria-controls="primary-services" aria-selected="false">Clinical Services</button></li>
      </ul>
      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="primary-tab-content">
<div class="tab-pane fade show active" id="primary-overview" role="tabpanel" aria-labelledby="primary-overview-tab" tabindex="0">
<div class="alert alert-light border mb-3"><strong>Disclaimer</strong><p class="p-modal mb-2">The medical facility classifications in this document organise Timor-Leste’s public primary-care network according to official facility type, population catchment, service package, referral role, and inpatient or observation capability. Primary care includes Health Posts, three levels of Community Health Centre, and mobile or community outreach services.</p><a href="https://apps.ms.gov.tl/hris/mdoc/PENSS%20II_eng.pdf" target="_blank" rel="noopener noreferrer">Timor-Leste Ministry of Health: National Health Sector Strategic Plan II 2020–2030</a></div>
<p class="p-modal">Primary medical facilities are the main community entry point into Timor-Leste’s public health system. Health Posts operate close to suco communities, while Community Health Centres provide broader clinical, maternity, diagnostic, public-health, and referral services at administrative-post or urban level. Outreach mechanisms, including Servisu Integradu Saúde Komunitária (SISCa) and Programa Integrado Saúde (PIS), extend services to communities with difficult geographic access.</p>
<p class="p-modal">A national health-facility survey conducted in 2024 and published in 2026 reported 417 government-run health facilities: one National Hospital, five Referral Hospitals, 72 Community Health Centres, and 339 Health Posts. The survey count is the most recent complete national facility total located for this document.</p>
<p class="p-modal">Note: Primary care is not a hospital grade. Health Posts and Level 1 Community Health Centres generally provide outpatient care. Level 2 and Level 3 Community Health Centres may operate limited observation, maternity, or inpatient beds, but do not have the specialist platform of a referral hospital.</p>
</div>
<div class="tab-pane fade" id="primary-role" role="tabpanel" aria-labelledby="primary-role-tab" tabindex="0">
<ul>
<li>Provide first-contact assessment and treatment for common acute and uncomplicated conditions</li>
<li>Deliver maternal, newborn, child, adolescent, reproductive, and family-health services</li>
<li>Manage tuberculosis, malaria, HIV, diarrhoeal, respiratory, and other communicable-disease programmes</li>
<li>Screen, treat, and follow hypertension, diabetes, cardiovascular risk, mental-health, nutrition, and other noncommunicable conditions</li>
<li>Provide immunisation, health promotion, disease prevention, surveillance, and community outreach</li>
<li>Stabilise urgent cases and refer patients requiring hospital admission, surgery, advanced diagnostics, specialist care, or critical care</li>
</ul>
</div>
<div class="tab-pane fade" id="primary-services" role="tabpanel" aria-labelledby="primary-services-tab" tabindex="0">
<h6 class="fw-bold mt-3">Approximate Bed Capacity: 0–20 beds, depending on facility type</h6>
<ul>
<li>Health Post: generally no regular inpatient ward. Expanded planning specifications may include up to four maternity or short-observation beds where designated.</li>
<li>Community Health Centre Level 1: outpatient and ambulatory facility; normally no regular inpatient beds.</li>
<li>Community Health Centre Level 2: may operate an inpatient department with up to 20 beds.</li>
<li>Community Health Centre Level 3: provides general outpatient and inpatient care; official models describe two to four observation beds or a ward of approximately 10–20 beds, with staffing norms generally below 20 beds.</li>
<li>SISCa, PIS, mobile, and outreach services: no fixed inpatient beds.</li>
</ul>
<h6 class="fw-bold mt-3">Primary Facility Types</h6>
<ul>
<li>Health Post — suco-level or community-proximate facility providing a basic primary-care package and referral</li>
<li>Community Health Centre Level 1 — ambulatory facility; typical catchment approximately 7,500–12,000 people in rural areas or about 15,000 in urban areas</li>
<li>Community Health Centre Level 2 — Level 1 services plus limited inpatient capacity; typical catchment about 20,000 people</li>
<li>Community Health Centre Level 3 — located in a municipal capital or large population concentration; general outpatient and inpatient care; typical catchment about 50,000 people</li>
<li>SISCa / PIS / mobile outreach — scheduled integrated services delivered closer to aldeias and remote communities</li>
</ul>
<h6 class="fw-bold mt-3">Core Services</h6>
<ul>
<li>General consultation and treatment of common illness and minor injury</li>
<li>Antenatal, delivery and maternity services at designated facilities, postnatal care, family planning, and reproductive health</li>
<li>Child-health, growth monitoring, nutrition assessment, immunisation, and developmental follow-up</li>
<li>Tuberculosis, malaria, HIV, leprosy, dengue, respiratory-disease, and outbreak surveillance or treatment according to programme scope</li>
<li>Hypertension, diabetes, cardiovascular-risk, mental-health, oral-health, eye-health, and chronic-disease care according to site capability</li>
<li>Health education, environmental health, community mobilisation, and household or outreach visits</li>
</ul>
<h6 class="fw-bold mt-3">Surgical &amp; Procedural Capacity</h6>
<ul>
<li>Wound care, dressings, injections, specimen collection, intravenous treatment, and minor outpatient procedures</li>
<li>Normal delivery and essential maternity procedures at designated facilities</li>
<li>Basic emergency resuscitation, airway support, oxygen, and stabilisation according to equipment and training</li>
<li>No routine major surgery, comprehensive anaesthesia service, or specialist intensive care</li>
<li>Referral and ambulance transfer for major trauma, surgical abdomen, high-risk obstetric emergency, severe neonatal illness, or critical disease</li>
</ul>
<h6 class="fw-bold mt-3">Diagnostic &amp; Support Infrastructure</h6>
<ul>
<li>Consultation, treatment, immunisation, maternal-child, pharmacy, and basic emergency areas</li>
<li>Rapid tests, specimen collection, basic laboratory, microscopy, or radiography according to CHC level and equipment</li>
<li>Maternity, observation, short-stay, or limited inpatient rooms at designated Level 2 and Level 3 CHCs</li>
<li>Cold-chain, medicine storage, public-health reporting, infection prevention, water, sanitation, and waste-management systems</li>
<li>Referral communication with municipal health services, referral hospitals, HNGV, and ambulance teams</li>
</ul>
<p class="p-modal">Note: The official CHC categories define functional scope and catchment rather than a single fixed bed requirement. Capacity varies with local population, geography, building design, staffing, equipment, and service readiness. Facility counts and bed use may change as new Health Posts, CHCs, inpatient rooms, and municipal hospitals become operational.</p>
</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level55Modal" tabindex="-1" aria-labelledby="secondaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
          <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" alt="" style="width:30px; height:30px;">
          <h5 class="modal-title" id="secondaryModalLabel">Secondary &mdash; Provincial Referral Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="secondary-tabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="secondary-overview-tab" data-bs-toggle="tab" data-bs-target="#secondary-overview" type="button" role="tab" aria-controls="secondary-overview" aria-selected="true">Overview</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="secondary-role-tab" data-bs-toggle="tab" data-bs-target="#secondary-role" type="button" role="tab" aria-controls="secondary-role" aria-selected="false">Role</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="secondary-services-tab" data-bs-toggle="tab" data-bs-target="#secondary-services" type="button" role="tab" aria-controls="secondary-services" aria-selected="false">Clinical Services</button></li>
      </ul>
      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="secondary-tab-content">
<div class="tab-pane fade show active" id="secondary-overview" role="tabpanel" aria-labelledby="secondary-overview-tab" tabindex="0">
<div class="alert alert-light border mb-3"><strong>Disclaimer</strong><p class="p-modal mb-2">The medical facility classifications in this document organise Timor-Leste’s public medical facilities according to the national health-service hierarchy, clinical capability, referral responsibility, and position in the patient-care pathway. Published hospital-specific bed counts are reported separately from Ministry planning and staffing norms.</p><a href="https://apps.ms.gov.tl/hris/mdoc/PENSS%20II_eng.pdf" target="_blank" rel="noopener noreferrer">Timor-Leste Ministry of Health: National Health Sector Strategic Plan II 2020–2030</a></div>
<p class="p-modal">Secondary medical facilities are Timor-Leste’s established referral hospitals outside Dili. They provide hospital-level emergency, outpatient, inpatient, medical, surgical, maternity, paediatric, diagnostic, and stabilisation services for defined regional or municipal catchments. They receive referrals from Community Health Centres and Health Posts and transfer cases requiring national specialist or tertiary care to HNGV.</p>
<p class="p-modal">Note: Current public sources commonly group Baucau, Maubisse, Maliana, Suai, and Oecusse as the five referral hospitals. The National Health Sector Strategic Plan further distinguishes Regional Hospitals and Municipal Hospitals. Baucau, Maubisse, and RAEOA/Oecusse appear under regional-hospital staffing norms, while Maliana and Covalima/Suai appear under municipal-hospital norms. This distinction does not change their shared secondary referral role.</p>
</div>
<div class="tab-pane fade" id="secondary-role" role="tabpanel" aria-labelledby="secondary-role-tab" tabindex="0">
<ul>
<li>The principal secondary referral hospital for a regional or multi-municipal catchment</li>
<li>Receive patients from Community Health Centres, Health Posts, municipal health services, outreach teams, ambulance services, and direct emergency presentation</li>
<li>Manage common and moderately complex medical, surgical, obstetric, paediatric, and emergency conditions</li>
<li>Provide inpatient admission, observation, surgery, diagnostics, maternity care, and specialist consultation according to local capability</li>
<li>Stabilise critically ill, injured, high-risk obstetric, neonatal, or surgical patients before transfer to HNGV</li>
<li>Support clinical supervision, laboratory networks, referral communication, and capacity development for primary facilities</li>
</ul>
</div>
<div class="tab-pane fade" id="secondary-services" role="tabpanel" aria-labelledby="secondary-services-tab" tabindex="0">
<h6 class="fw-bold mt-3">Approximate Bed Capacity: 24–75 beds</h6>
<ul>
<li>Eduardo Ximenes Referral Hospital, Baucau: approximately 75 beds</li>
<li>Maubisse Referral Hospital: approximately 24 beds</li>
<li>Maliana Referral Hospital: approximately 24 beds</li>
<li>Suai Referral Hospital, Covalima: approximately 24 beds</li>
<li>Oecusse Referral Hospital, RAEOA: approximately 24 beds</li>
</ul>
<p class="p-modal">These facility-level figures come from a peer-reviewed study published in 2024 using 2020–2021 hospital surveys. No newer complete public hospital-bed registry was located. The NHSSP II staffing norms use approximately 25–30 beds for municipal hospitals and above 75 beds for regional hospitals, showing the intended capacity bands rather than a confirmed current count for every institution.</p>
<h6 class="fw-bold mt-3">Established Public Referral Hospitals</h6>
<ul>
<li>Eduardo Ximenes Referral / Regional Hospital — Baucau</li>
<li>Maubisse Referral Hospital — Ainaro Municipality</li>
<li>Maliana Referral Hospital — Bobonaro Municipality</li>
<li>Suai Referral Hospital — Covalima Municipality</li>
<li>Oecusse Referral Hospital — Special Administrative Region of Oecusse-Ambeno (RAEOA)</li>
</ul>
<h6 class="fw-bold mt-3">Core Specialties</h6>
<ul>
<li>General and acute internal medicine</li>
<li>General surgery and emergency surgical treatment according to staff and theatre readiness</li>
<li>Paediatrics and child-health services</li>
<li>Obstetrics, gynaecology, maternity, and emergency caesarean capability according to site</li>
<li>Emergency, trauma stabilisation, anaesthesia, and inpatient nursing</li>
<li>Selected dental, mental-health, rehabilitation, communicable-disease, and noncommunicable-disease services</li>
</ul>
<h6 class="fw-bold mt-3">Intermediate Services</h6>
<ul>
<li>Emergency assessment, inpatient care, observation, stabilisation, and referral</li>
<li>High-dependency care established across all five referral hospitals, with capability varying by trained staff, oxygen, equipment, and maintenance</li>
<li>Outpatient consultation, antenatal and postnatal care, chronic-disease management, pharmacy, rehabilitation, and follow-up</li>
<li>Referral coordination with HNGV, ambulance services, Community Health Centres, and municipal health services</li>
<li>Laboratory and antimicrobial-resistance surveillance support through the national referral-hospital network</li>
</ul>
<h6 class="fw-bold mt-3">Surgical &amp; Procedural Capacity</h6>
<ul>
<li>Common emergency and elective surgery according to local surgeon, anaesthesia, theatre, and blood availability</li>
<li>Caesarean delivery and other essential obstetric procedures at equipped hospitals</li>
<li>Appendectomy, wound management, trauma procedures, and other general surgery according to capability</li>
<li>Emergency airway, ventilation, resuscitation, and high-dependency interventions</li>
<li>Transfer to HNGV for complex surgery, advanced critical care, specialist imaging, or services unavailable locally</li>
</ul>
<h6 class="fw-bold mt-3">Diagnostic &amp; Support Infrastructure</h6>
<ul>
<li>Hospital laboratory and microbiology capacity, with specimen referral to national services when required</li>
<li>Radiography, ultrasound, basic cardiac testing, and other diagnostics according to facility equipment</li>
<li>Operating theatre, recovery, maternity, emergency, oxygen, pharmacy, and infection-prevention infrastructure</li>
<li>Blood storage or transfusion support according to site and national supply arrangements</li>
<li>Telemedicine capability under proof-of-concept deployment at Baucau Regional Hospital, linked to HNGV</li>
</ul>
<p class="p-modal">Note: Municipal Hospital Development</p>
<p class="p-modal">The Government is developing additional municipal hospital infrastructure and inpatient capacity. These projects should not be counted as fully operational secondary hospitals until construction, equipment installation, staffing, licensing or administrative commissioning, and routine service activation are confirmed. In July 2026, WHO reported that the Viqueque Municipal Hospital was still awaiting completion.</p>
</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level66Modal" tabindex="-1" aria-labelledby="tertiaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
          <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" alt="" style="width:30px; height:30px;">
          <h5 class="modal-title" id="tertiaryModalLabel">Tertiary &mdash; National Referral Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="tertiary-tabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="tertiary-overview-tab" data-bs-toggle="tab" data-bs-target="#tertiary-overview" type="button" role="tab" aria-controls="tertiary-overview" aria-selected="true">Overview</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="tertiary-role-tab" data-bs-toggle="tab" data-bs-target="#tertiary-role" type="button" role="tab" aria-controls="tertiary-role" aria-selected="false">Role</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="tertiary-services-tab" data-bs-toggle="tab" data-bs-target="#tertiary-services" type="button" role="tab" aria-controls="tertiary-services" aria-selected="false">Clinical Services</button></li>
      </ul>
      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="tertiary-tab-content">
<div class="tab-pane fade show active" id="tertiary-overview" role="tabpanel" aria-labelledby="tertiary-overview-tab" tabindex="0">
<div class="alert alert-light border mb-3"><strong>Disclaimer</strong><p class="p-modal mb-2">The medical facility classifications in this document organize Timor-Leste’s public medical facilities according to the national health-service hierarchy, clinical capability, referral responsibility, and position in the patient-care pathway. Bed numbers are included as approximate capacity indicators; they do not independently determine the level of care.</p><a href="https://apps.ms.gov.tl/hris/mdoc/PENSS%20II_eng.pdf" target="_blank" rel="noopener noreferrer">Timor-Leste Ministry of Health: National Health Sector Strategic Plan II 2020–2030</a></div>
<p class="p-modal">Tertiary medical care forms Timor-Leste’s highest domestic clinical referral level. Hospital Nacional Guido Valadares (HNGV) in Dili is the national hospital and the country’s only comprehensive tertiary referral hospital. It manages the most complex cases that can be treated nationally, supports specialist and critical-care services, receives referrals from all municipalities, and coordinates overseas referral when required care is not available in Timor-Leste.</p>
<p class="p-modal">Note: Timor-Leste’s health-system hierarchy formally places the National Hospital above Regional Hospitals, Municipal Hospitals, Community Health Centres, and Health Posts. Tertiary status is therefore based on HNGV’s national referral responsibility and advanced service role rather than a fixed statutory bed threshold. Selected highly specialised treatment remains dependent on visiting teams, international partnerships, or approved overseas referral.</p>
</div>
<div class="tab-pane fade" id="tertiary-role" role="tabpanel" aria-labelledby="tertiary-role-tab" tabindex="0">
<ul>
<li>The national referral hospital for complex, severe, high-risk, and uncommon conditions</li>
<li>Receive referrals from the five referral hospitals, municipal health services, Community Health Centres, Health Posts, ambulance services, and direct emergency presentation</li>
<li>Provide the highest available domestic level of medical, surgical, obstetric, paediatric, neonatal, diagnostic, emergency, and critical care</li>
<li>Coordinate multidisciplinary care, specialist consultation, rehabilitation, and long-term follow-up</li>
<li>Stabilise patients who require treatment abroad and coordinate approved overseas referral pathways</li>
<li>Support clinical teaching, specialist training, national protocols, research, and health-service development</li>
</ul>
</div>
<div class="tab-pane fade" id="tertiary-services" role="tabpanel" aria-labelledby="tertiary-services-tab" tabindex="0">
<h6 class="fw-bold mt-3">Approximate Bed Capacity: 425–450 beds</h6>
<p class="p-modal">A senior HNGV official reported approximately 425–450 beds in use in August 2025. A January 2025 report gave a closely aligned range of 420–450 beds.</p>
<p class="p-modal">A peer-reviewed 2024 hospital study, based on 2020–2021 surveys, described HNGV as a 250-bed hospital. The difference reflects later expansion and operational use; Timor-Leste does not publish a continuously updated central licensed-bed registry.</p>
<p class="p-modal">The new five-storey paediatric and intensive cardiac-care building is not added to the operational total. It had not yet been handed over in late May 2026, and no verified commissioning notice was located by 5 August 2026.</p>
<h6 class="fw-bold mt-3">Main Public Tertiary Hospital</h6>
<p class="p-modal">Hospital Nacional Guido Valadares (HNGV), Dili — national hospital and highest domestic referral institution</p>
<h6 class="fw-bold mt-3">Associated National and Specialist Support</h6>
<ul>
<li>National laboratory, blood-service, pharmacy, ambulance, public-health, and referral functions support HNGV and the wider national network</li>
<li>Hospital António Carvalho at Lahane may support overflow, palliative, respiratory, cardiac, or other designated services according to Ministry direction; it should not be treated as a second comprehensive tertiary hospital</li>
<li>Overseas referral remains part of tertiary care for procedures, diagnostics, or subspecialties not sustainably available in-country</li>
</ul>
<h6 class="fw-bold mt-3">Core Specialties</h6>
<ul>
<li>Advanced internal medicine and selected medical subspecialties</li>
<li>General surgery and selected surgical specialties</li>
<li>Obstetrics, gynaecology, paediatrics, neonatal care, and high-risk maternal-child care</li>
<li>Emergency medicine, anaesthesia, intensive care, and high-dependency care</li>
<li>Orthopaedic, ophthalmic, dental, mental-health, renal, cardiac, oncology, and other specialist services according to available staff, equipment, and programme capacity</li>
<li>National consultation and referral support for complex communicable and noncommunicable disease cases</li>
</ul>
<h6 class="fw-bold mt-3">Intermediate Services</h6>
<ul>
<li>Twenty-four-hour emergency assessment, inpatient admission, stabilisation, and critical care</li>
<li>Specialist outpatient clinics, multidisciplinary review, day treatment, and follow-up</li>
<li>Pharmacy, blood transfusion, rehabilitation, physiotherapy, nutrition, social support, and specialist nursing</li>
<li>Referral coordination with regional and municipal services, primary care, ambulance teams, and overseas providers</li>
<li>Teaching, clinical supervision, protocol development, and technical support for lower-level facilities</li>
</ul>
<h6 class="fw-bold mt-3">Surgical &amp; Procedural Capacity</h6>
<ul>
<li>Major elective and emergency surgery according to specialist and theatre availability</li>
<li>General, obstetric, orthopaedic, paediatric, abdominal, trauma, and other specialist procedures</li>
<li>Anaesthesia, operating-theatre, recovery, peri-operative, and post-operative critical-care support</li>
<li>Endoscopy, dialysis, selected interventional procedures, and specialist treatment according to operational equipment</li>
<li>Stabilisation and overseas transfer for procedures that exceed national capability</li>
</ul>
<h6 class="fw-bold mt-3">Diagnostic &amp; Support Infrastructure</h6>
<ul>
<li>Hospital laboratory, microbiology, blood-bank and transfusion, pathology, and specimen-referral capacity</li>
<li>Radiography, ultrasound, and advanced imaging or specialist diagnostics according to equipment availability</li>
<li>Intensive-care monitoring, ventilation, oxygen, isolation, emergency, theatre, and sterile-supply systems</li>
<li>Pharmacy, medical-gas, infection-prevention, waste-management, and equipment-maintenance infrastructure</li>
<li>Health-information, teaching, teleconsultation, and international referral coordination systems</li>
<li>Note: HNGV is the country’s single national tertiary platform. The latest operational-bed figure is based on direct hospital reporting rather than a national bed register. New buildings, planned specialist centres, and beds under procurement should not be counted until they are equipped, staffed, commissioned, and placed into routine service.</li>
</ul>
</div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('service')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd-WVlGgZFJwAtPZkbAEca2Np6OI7CBTM&libraries=places,geometry"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const embassyData = {!! json_encode([
        'id'        => $embassy->id,
        'name'      => $embassy->name_embassiees,
        'latitude'  => $embassy->latitude,
        'longitude' => $embassy->longitude,
        'image'     => $embassy->image ?? '',
        'location'  => $embassy->location ?? '',
        'telephone' => $embassy->telephone ?? '',
        'website'   => $embassy->website ?? '',
    ]) !!};

    const nearbyHospitals = @json($nearbyHospitals);
    const nearbyAirports = @json($nearbyAirports);
    const nearbyPolices = @json($nearbyPolices);
    const nearbyEmbassy = @json($nearbyEmbassy);
    let radiusKm = 100; // default radius

    let map, mainMarker, radiusCircle, directionsService, directionsRenderer;
    let nearbyMarkersGroup = [];
    let searchLocation = null;
    let searchMarker = null;

    // === ICON DEFAULT ===
    const DEFAULT_HOSPITAL_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';
    const DEFAULT_AIRPORT_ICON_URL  = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';
    const DEFAULT_MAIN_EMBASSY_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';
    const DEFAULT_POLICE_ICON_URL = 'https://png.pngtree.com/png-vector/20221211/ourmid/pngtree-minimal-location-map-icon-logo-symbol-vector-design-transparent-background-png-image_6520892.png';
    const DEFAULT_EMBASSY_ICON_URL = '/images/embassy-icon-new.png';

    // === INISIALISASI PETA ===
    function initializeMap() {
        const center = new google.maps.LatLng(embassyData.latitude, embassyData.longitude);
        map = new google.maps.Map(document.getElementById('map'), {
            center: center,
            zoom: 11,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: true,
            fullscreenControl: true,
            streetViewControl: false
        });

        const directionsPanel = document.createElement('div');
        directionsPanel.id = 'directionsPanel';
        directionsPanel.style.width = '370px';
        directionsPanel.style.maxHeight = '450px';
        directionsPanel.style.overflowY = 'auto';
        directionsPanel.style.backgroundColor = 'white';
        directionsPanel.style.display = 'none';
        directionsPanel.style.boxShadow = '0 4px 20px rgba(0,0,0,0.2)';
        directionsPanel.style.borderRadius = '12px';
        directionsPanel.style.margin = '10px';
        directionsPanel.style.padding = '0';
        directionsPanel.style.fontSize = '13px';

        // Header
        const dpHeader = document.createElement('div');
        dpHeader.className = 'dp-header';
        dpHeader.innerHTML = `
            <div class="dp-header-title">
                <i class="fas fa-route"></i> Route Directions
            </div>
            <button class="dp-close-btn" title="Close">
                <i class="fas fa-times"></i>
            </button>
        `;
        directionsPanel.appendChild(dpHeader);

        // Content area (Google renders steps here)
        const dpContent = document.createElement('div');
        dpContent.style.padding = '10px';
        directionsPanel.appendChild(dpContent);

        // Close button handler
        dpHeader.querySelector('.dp-close-btn').addEventListener('click', () => {
            directionsPanel.style.display = 'none';
            directionsRenderer.setDirections({routes: []});
        });

        google.maps.event.addDomListener(directionsPanel, 'click', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'dblclick', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'mousedown', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'touchstart', e => e.stopPropagation());
        google.maps.event.addDomListener(directionsPanel, 'wheel', e => e.stopPropagation());

        map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(directionsPanel);

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            panel: dpContent,
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#1a73e8',
                strokeOpacity: 0.8,
                strokeWeight: 5
            }
        });
    }

    function addMainEmbassyAndCircle() {
        mainMarker = new google.maps.Marker({
            position: new google.maps.LatLng(embassyData.latitude, embassyData.longitude),
            map: map,
            icon: {
                url: DEFAULT_MAIN_EMBASSY_ICON_URL,
                scaledSize: new google.maps.Size(25, 41)
            },
            title: embassyData.name
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `<b>${embassyData.name}</b><br>This is the main embassy.`
        });

        mainMarker.addListener('click', () => {
            infoWindow.open(map, mainMarker);
        });

        radiusCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.1,
            map: map,
            center: { lat: parseFloat(embassyData.latitude), lng: parseFloat(embassyData.longitude) },
            radius: radiusKm * 1000
        });
    }

    function clearNearbyMarkers() {
        for (let i = 0; i < nearbyMarkersGroup.length; i++) {
            nearbyMarkersGroup[i].setMap(null);
        }
        nearbyMarkersGroup = [];
    }

    // === Tambahkan Marker Sekitar ===
    function addNearbyMarkers(data, defaultIconUrl, type, filters = {}) {
        data.forEach(item => {
            const distance = calculateDistance(
                embassyData.latitude, embassyData.longitude,
                item.latitude, item.longitude
            );
            if (distance > radiusKm) return;

            // Filter hospital
            if (type === 'Hospital' && filters.hospitalLevels?.length > 0) {
                const level = (item.facility_level || '').toLowerCase();
                const allowed = filters.hospitalLevels.map(l => l.toLowerCase());
                if (!allowed.includes(level)) return;
            }

            // Filter airport
            if (type === 'Airport' && filters.airportClassifications?.length > 0) {
                const categories = (item.category || '').split(',').map(c => c.trim().toLowerCase());
                const allowed = filters.airportClassifications.map(c => c.toLowerCase());
                if (!categories.some(cat => allowed.includes(cat))) return;
            }

            // Filter police
            if (type === 'Police' && filters.policeCategories?.length > 0) {
                const categories = (item.category || '').split(',').map(c => c.trim().toLowerCase());
                const allowed = filters.policeCategories.map(c => c.toLowerCase());
                if (!categories.some(cat => allowed.includes(cat))) return;
            }

            const isPolice = type === 'Police';
            const iconSize = isPolice ? new google.maps.Size(12, 12) : new google.maps.Size(24, 24);

            const marker = new google.maps.Marker({
                position: { lat: parseFloat(item.latitude), lng: parseFloat(item.longitude) },
                map: map,
                icon: {
                    url: item.icon || defaultIconUrl,
                    scaledSize: iconSize
                }
            });

            const name = item.name || item.airport_name || item.name_police || item.name_embassiees || 'N/A';
            const level = item.facility_level || item.category || '';

            let url = '#';
            if (type === 'Airport') url = `/airports/${item.id}/detail`;
            else if (type === 'Hospital') url = `/hospitals/${item.id}`;
            else if (type === 'Police') url = `/police/${item.id}/detail`;
            else if (type === 'Embassy') url = `/embassiees/${item.id}/detail`;

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="font-size:13px;">
                        <a href="${url}" target="_blank">${name}</a><br>
                        ${level}<br>
                        <strong>Distance:</strong> ${distance.toFixed(2)} km<br>
                        <button class="btn btn-sm btn-primary mt-2"
                            onclick="getDirection(${item.latitude}, ${item.longitude})">
                            Get Direction
                        </button>
                    </div>
                `
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });

            nearbyMarkersGroup.push(marker);
        });
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2 +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    // === NEARBY HOTELS (shown once a location is searched) ===
    let categoryMarkers   = [];
    let activeCategoryBtn = null;
    let categoryBar       = null;

    function resetCategoryBtn(btn) {
        btn.style.background  = '#fff';
        btn.style.color       = '#222';
        btn.style.borderColor = 'rgba(0,0,0,0.12)';
    }

    function clearCategoryMarkers() {
        categoryMarkers.forEach(m => m.setMap(null));
        categoryMarkers = [];
    }

    function showNearbyCategory(type, label) {
        if (!searchLocation) return;
        clearCategoryMarkers();

        const center  = new google.maps.LatLng(searchLocation.lat, searchLocation.lng);
        const service = new google.maps.places.PlacesService(map);

        const iconColors = { lodging: '#1a73e8' };
        const color = iconColors[type] || '#555';

        function makeSvgIcon(col) {
            const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='32' height='40' viewBox='0 0 32 40'>`
                      + `<path d='M16 0C7.16 0 0 7.16 0 16c0 12 16 24 16 24S32 28 32 16C32 7.16 24.84 0 16 0z' fill='${col}'/>`
                      + `<circle cx='16' cy='16' r='7' fill='#fff'/>`
                      + `</svg>`;
            return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
        }

        service.nearbySearch({ location: center, radius: 5000, type }, (results, status) => {
            if (status !== google.maps.places.PlacesServiceStatus.OK) {
                if (status === 'ZERO_RESULTS') {
                    alert(`No ${label.toLowerCase()} found within 5 km.`);
                } else {
                    alert(`Failed to load ${label.toLowerCase()}. Error status: ${status}. Please ensure "Places API" is enabled and billing is active.`);
                    console.error('PlacesService nearbySearch failed with status:', status);
                }
                return;
            }
            if (!results.length) return;

            results.forEach(place => {
                if (!place.geometry?.location) return;

                const marker = new google.maps.Marker({
                    position: place.geometry.location,
                    map,
                    title: place.name,
                    icon: { url: makeSvgIcon(color), scaledSize: new google.maps.Size(32, 40) },
                    animation: google.maps.Animation.DROP
                });

                const dist     = google.maps.geometry.spherical.computeDistanceBetween(center, place.geometry.location);
                const distText = dist >= 1000 ? (dist / 1000).toFixed(1) + ' km' : Math.round(dist) + ' m';
                const rating   = place.rating ? `⭐ ${place.rating.toFixed(1)}` : '';
                const destLat  = place.geometry.location.lat();
                const destLng  = place.geometry.location.lng();

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="font-size:13px;min-width:190px;">
                            <h5 style="border-bottom:1px solid #ccc;margin:0 0 6px;font-size:14px;">${place.name}</h5>
                            <div style="color:#666;font-size:12px;margin-bottom:3px;">${label}</div>
                            ${rating  ? `<div style="font-size:12px;">${rating}</div>` : ''}
                            <div style="margin-top:4px;font-size:12px;color:#555;"> ${distText} from search location</div>
                            <button class="btn btn-sm btn-primary mt-2"
                                onclick="getDirection(${destLat}, ${destLng})">
                                Get Direction
                            </button>
                        </div>`
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                categoryMarkers.push(marker);
            });
        });
    }

    function setupNearbyCategoryBar() {
        categoryBar = document.createElement('div');
        categoryBar.id = 'nearbyCategBar';
        Object.assign(categoryBar.style, {
            display:       'none',
            background:    'transparent',
            padding:       '8px 10px 0',
            gap:           '8px',
            flexWrap:      'nowrap',
            overflowX:     'auto',
            maxWidth:      '90vw',
            scrollbarWidth:'none'
        });

        const nearbyCategories = [
            { label: 'Hotels', icon: '🏨', type: 'lodging' }
        ];

        nearbyCategories.forEach(cat => {
            const btn = document.createElement('button');
            btn.textContent = cat.icon + ' ' + cat.label;
            Object.assign(btn.style, {
                display:      'inline-flex',
                alignItems:   'center',
                gap:          '4px',
                padding:      '6px 14px',
                borderRadius: '20px',
                border:       '1px solid rgba(0,0,0,0.12)',
                background:   '#fff',
                color:        '#222',
                fontSize:     '13px',
                fontWeight:   '500',
                cursor:       'pointer',
                whiteSpace:   'nowrap',
                boxShadow:    '0 1px 4px rgba(0,0,0,0.15)',
                transition:   'all 0.15s'
            });

            btn.addEventListener('click', () => {
                if (activeCategoryBtn === btn) {
                    clearCategoryMarkers();
                    resetCategoryBtn(btn);
                    activeCategoryBtn = null;
                    return;
                }
                if (activeCategoryBtn) resetCategoryBtn(activeCategoryBtn);
                activeCategoryBtn = btn;
                btn.style.background = '#1a73e8';
                btn.style.color      = '#fff';
                btn.style.borderColor= '#1a73e8';
                showNearbyCategory(cat.type, cat.label);
            });

            categoryBar.appendChild(btn);
        });

        map.controls[google.maps.ControlPosition.TOP_CENTER].push(categoryBar);
    }

    // === ROUTING ===
    window.getDirection = function(lat, lng) {
        const origin = searchLocation
            ? new google.maps.LatLng(searchLocation.lat, searchLocation.lng)
            : new google.maps.LatLng(embassyData.latitude, embassyData.longitude);

        directionsService.route({
            origin: origin,
            destination: new google.maps.LatLng(lat, lng),
            travelMode: 'DRIVING'
        }, (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
                const panel = document.getElementById('directionsPanel');
                if(panel) panel.style.display = 'block';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Route Not Found',
                    text: status === 'ZERO_RESULTS'
                        ? 'No driving route could be found between these two locations.'
                        : 'Directions request failed (' + status + ').',
                    confirmButtonColor: '#d33'
                });
            }
        });
    };

    function fitMapToBounds() {
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(embassyData.latitude, embassyData.longitude));
        if (searchLocation) {
            bounds.extend(new google.maps.LatLng(searchLocation.lat, searchLocation.lng));
        }
        nearbyMarkersGroup.forEach(m => bounds.extend(m.getPosition()));

        const circleBounds = radiusCircle.getBounds();
        if(circleBounds) {
            bounds.union(circleBounds);
        }

        map.fitBounds(bounds);
    }

    function updateMarkers(filterType, hospitalLevels, airportClassifications, policeCategories) {
        clearNearbyMarkers();
        if (radiusCircle) radiusCircle.setMap(null);
        addMainEmbassyAndCircle();

        const filters = { hospitalLevels, airportClassifications, policeCategories };
        if (filterType === 'hospital') {
            addNearbyMarkers(nearbyHospitals, DEFAULT_HOSPITAL_ICON_URL, 'Hospital', filters);
        } else if (filterType === 'airport') {
            addNearbyMarkers(nearbyAirports, DEFAULT_AIRPORT_ICON_URL, 'Airport', filters);
        } else if (filterType === 'police') {
            addNearbyMarkers(nearbyPolices, DEFAULT_POLICE_ICON_URL, 'Police', filters);
        } else if (filterType === 'embassy') {
            addNearbyMarkers(nearbyEmbassy, DEFAULT_EMBASSY_ICON_URL, 'Embassy', filters);
        } else {
            addNearbyMarkers(nearbyHospitals, DEFAULT_HOSPITAL_ICON_URL, 'Hospital', filters);
            addNearbyMarkers(nearbyAirports, DEFAULT_AIRPORT_ICON_URL, 'Airport', filters);
            addNearbyMarkers(nearbyPolices, DEFAULT_POLICE_ICON_URL, 'Police', filters);
            addNearbyMarkers(nearbyEmbassy, DEFAULT_EMBASSY_ICON_URL, 'Embassy', filters);
        }

        fitMapToBounds();
    }

    // === FILTER CONTROL ===
    function setupFilterControl() {
        const container = document.createElement('div');
        container.className = 'p-2 bg-white rounded';
        container.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
        container.style.width = '220px';
        container.style.maxHeight = '75vh';
        container.style.overflowY = 'auto';
        container.style.marginRight = '10px';
        container.style.marginTop = '10px';
        container.style.cursor = 'default';

        container.innerHTML = `
            <h6><strong>Filter</strong></h6>

            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Search Location</strong>
            <div style="position:relative;margin-top:5px;">
                <input type="text" id="gmSearchInput" class="form-control form-control-sm"
                    placeholder="Search Location..." autocomplete="off" style="padding-right:28px;">
                <i class="fas fa-times" id="gmClearBtn"
                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);color:#70757a;font-size:13px;cursor:pointer;display:none;"></i>
            </div>

            <label><strong>Radius:</strong> <span id="radiusLabel">${radiusKm}</span> km</label>
            <input type="range" id="radiusRange" min="10" max="500" step="10" value="${radiusKm}" class="form-range mb-2" style="display:block;width:100%;">

            <select id="mapFilter" class="form-select form-select-sm mb-2" style="display:block;width:100%;">
                <option value="all">Show All</option>
                <option value="hospital">Hospitals</option>
                <option value="airport">Aviation</option>
                <option value="police">Police</option>
                <option value="embassy">Embassy</option>
            </select>

            <div id="hospitalFilter" style="display:none;">
                <strong>Facility Level:</strong><br>
                ${['Tertiary','Secondary','Primary']
                    .map(lvl => `<label style="display:block;font-size:13px;">
                        <input type="checkbox" name="hospitalLevel" value="${lvl}"> ${lvl}
                    </label>`).join('')}
            </div>

            <div id="airportFilter" style="display:none;margin-top:8px;">
                <strong>Category:</strong><br>
                ${['International','Domestic','Military','Regional','Private']
                    .map(cls => `<label style="display:block;font-size:13px;">
                        <input type="checkbox" name="airportClass" value="${cls}"> ${cls}
                    </label>`).join('')}
            </div>

            <div id="policeFilter" style="display:none;margin-top:8px;">
                <strong>Police Category:</strong><br>
                ${[
                'National Police (HQ)',
                'Municipal Police',
                'Police Station',
                'Local Police Posts'
            ].map(cat => `
                    <label style="display:block;font-size:13px;">
                        <input type="checkbox" name="policeCategory" value="${cat}"> ${cat}
                    </label>
                `).join('')}
            </div>

            <button id="resetFilter" class="btn btn-sm btn-secondary mt-3 w-100">Reset Filter</button>
        `;

        // Prevent events from passing to the map
        google.maps.event.addDomListener(container, 'click', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'dblclick', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'mousedown', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'touchstart', e => e.stopPropagation());
        google.maps.event.addDomListener(container, 'wheel', e => e.stopPropagation());

        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(container);

        const radiusSlider = container.querySelector('#radiusRange');
        const radiusLabel = container.querySelector('#radiusLabel');
        radiusSlider.addEventListener('input', () => {
            radiusKm = parseInt(radiusSlider.value);
            radiusLabel.textContent = radiusKm;
            refreshFilters();
        });

        const filterSelect = container.querySelector('#mapFilter');
        const hospitalDiv = container.querySelector('#hospitalFilter');
        const airportDiv = container.querySelector('#airportFilter');
        const policeDiv = container.querySelector('#policeFilter');
        const resetBtn = container.querySelector('#resetFilter');

        function refresh() {
            const selectedType = filterSelect.value;
            const selectedHospitalLevels = Array.from(container.querySelectorAll('input[name="hospitalLevel"]:checked')).map(el => el.value);
            const selectedAirportClasses = Array.from(container.querySelectorAll('input[name="airportClass"]:checked')).map(el => el.value);
            const selectedPoliceCategories = Array.from(container.querySelectorAll('input[name="policeCategory"]:checked')).map(el => el.value);
            updateMarkers(selectedType, selectedHospitalLevels, selectedAirportClasses, selectedPoliceCategories);
        }

        filterSelect.addEventListener('change', () => {
            const val = filterSelect.value;
            hospitalDiv.style.display = val === 'hospital' ? 'block' : 'none';
            airportDiv.style.display = val === 'airport' ? 'block' : 'none';
            policeDiv.style.display = val === 'police' ? 'block' : 'none';
            refresh();
        });

        container.querySelectorAll('input[name="hospitalLevel"]').forEach(chk => chk.addEventListener('change', refresh));
        container.querySelectorAll('input[name="airportClass"]').forEach(chk => chk.addEventListener('change', refresh));
        container.querySelectorAll('input[name="policeCategory"]').forEach(chk => chk.addEventListener('change', refresh));

        resetBtn.addEventListener('click', () => {
            container.querySelectorAll('input[type="checkbox"]').forEach(chk => chk.checked = false);
            filterSelect.value = 'all';
            hospitalDiv.style.display = 'none';
            airportDiv.style.display = 'none';
            policeDiv.style.display = 'none';
            radiusKm = 100;
            radiusSlider.value = radiusKm;
            radiusLabel.textContent = radiusKm;

            const gmInput = container.querySelector('#gmSearchInput');
            if(gmInput) gmInput.value = '';

            if (searchMarker) {
                searchMarker.setMap(null);
                searchMarker = null;
            }
            searchLocation = null;

            if (categoryBar) categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

            directionsRenderer.setDirections({routes: []});
            const panel = document.getElementById('directionsPanel');
            if(panel) panel.style.display = 'none';

            refresh();
        });

        return container;
    }

    function refreshFilters() {
        const selectedType = document.querySelector('#mapFilter')?.value || 'all';
        const selectedHospitalLevels = Array.from(document.querySelectorAll('input[name="hospitalLevel"]:checked')).map(el => el.value);
        const selectedAirportClasses = Array.from(document.querySelectorAll('input[name="airportClass"]:checked')).map(el => el.value);
        const selectedPoliceCategories = Array.from(document.querySelectorAll('input[name="policeCategory"]:checked')).map(el => el.value);
        updateMarkers(selectedType, selectedHospitalLevels, selectedAirportClasses, selectedPoliceCategories);
    }

    // === SEARCH LOCATION CONTROL (now part of the filter panel) ===
    function setupSearchControl(filterContainer) {
        const input = filterContainer.querySelector('#gmSearchInput');
        const clearBtn = filterContainer.querySelector('#gmClearBtn');
        if (!input || !clearBtn) return;

        input.addEventListener('keydown', (e) => {
            if(e.key === 'Enter') e.preventDefault();
        });

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        // The input lives inside a custom map control, so Google's ".pac-container"
        // dropdown (appended to <body> with position:absolute) ends up clipped/
        // hidden behind the map's own control panes. Force position:fixed and keep
        // re-applying it, since Google resets the container's inline style on every
        // prediction update (a one-shot fix gets silently overwritten).
        let pacContainer = null;

        function fixPacPosition() {
            if (!pacContainer) return;
            if (pacContainer.parentElement !== document.body) {
                document.body.appendChild(pacContainer);
            }
            const rect = input.getBoundingClientRect();
            pacContainer.style.position = 'fixed';
            pacContainer.style.zIndex = '2147483647';
            pacContainer.style.top = (rect.bottom + 2) + 'px';
            pacContainer.style.left = rect.left + 'px';
            pacContainer.style.width = rect.width + 'px';
            pacContainer.style.visibility = 'visible';
            pacContainer.style.opacity = '1';
            pacContainer.style.pointerEvents = 'auto';
        }

        function claimPacContainer() {
            if (pacContainer) return true;
            pacContainer = document.querySelector('.pac-container');
            if (pacContainer) {
                fixPacPosition();
                new MutationObserver(fixPacPosition).observe(
                    pacContainer, { attributes: true, attributeFilter: ['style'] }
                );
                return true;
            }
            return false;
        }

        const pacObserver = new MutationObserver(() => claimPacContainer());
        pacObserver.observe(document.body, { childList: true, subtree: true });

        // Fallback in case Google created ".pac-container" before the observer
        // above started watching (a MutationObserver only reports *future*
        // mutations, so a container created earlier would otherwise be missed).
        if (!claimPacContainer()) {
            const pollId = setInterval(() => {
                if (claimPacContainer()) clearInterval(pollId);
            }, 200);
            setTimeout(() => clearInterval(pollId), 10000);
        }

        window.addEventListener('scroll', fixPacPosition, true);
        window.addEventListener('resize', fixPacPosition);
        input.addEventListener('focus', fixPacPosition);
        input.addEventListener('input', fixPacPosition);

        input.addEventListener('input', (e) => {
            if (e.target.value.length > 0) {
                clearBtn.style.display = 'block';
            } else {
                clearBtn.style.display = 'none';
            }
        });

        clearBtn.addEventListener('click', () => {
            input.value = '';
            clearBtn.style.display = 'none';
            input.focus();
            if (pacContainer) pacContainer.style.display = 'none';

            if (searchMarker) {
                searchMarker.setMap(null);
                searchMarker = null;
            }
            searchLocation = null;

            if (categoryBar) categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

            directionsRenderer.setDirections({routes: []});
            const panel = document.getElementById('directionsPanel');
            if(panel) panel.style.display = 'none';
        });

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                return;
            }

            if (searchMarker) searchMarker.setMap(null);

            searchMarker = new google.maps.Marker({
                map: map,
                position: place.geometry.location,
                icon: {
                    url: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    scaledSize: new google.maps.Size(25, 41)
                }
            });

            const lat = place.geometry.location.lat();
            const lon = place.geometry.location.lng();
            searchLocation = { lat: lat, lng: lon };

            if (categoryBar) categoryBar.style.display = 'flex';

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="font-size:13px;">
                        <b>${place.name}</b><br>
                        <small>Lat: ${lat.toFixed(5)}, Lng: ${lon.toFixed(5)}</small><br>
                        <button class="btn btn-sm btn-primary mt-2"
                            onclick="getDirection(${embassyData.latitude}, ${embassyData.longitude})">
                            Get Direction to Main Embassy
                        </button>
                    </div>
                `
            });

            infoWindow.open(map, searchMarker);
            searchMarker.addListener('click', () => {
                infoWindow.open(map, searchMarker);
            });

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(14);
            }
        });
    }

    // === JALANKAN ===
    initializeMap();
    addMainEmbassyAndCircle();
    updateMarkers('all', [], [], []);
    const filterContainer = setupFilterControl();
    setupSearchControl(filterContainer);
    setupNearbyCategoryBar();
});
</script>

@endpush
