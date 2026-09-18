@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-title', 'East Timor Crisis Management Tools')

@push('styles')


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        #map {
            height: 700px;
        }
        .filter-container {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        /* === Facilities filter list (map panel) === */
        .facility-list {
            margin-top: 8px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .facility-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 1px 6px;
            border-radius: 5px;
            transition: background-color .15s ease;
        }
        .facility-item:hover {
            background-color: #f4f7fb;
        }
        /* Bootstrap 4 (AdminLTE) sets .form-check-input to position:absolute with a
           negative left margin, which makes the box overlap the label text here. */
        .facility-item .form-check-input {
            position: static;
            float: none;
            flex: 0 0 15px;
            width: 15px;
            height: 15px;
            margin: 0;
            cursor: pointer;
        }
        .facility-item .form-check-label {
            flex: 1 1 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin: 0;
            font-size: 13px;
            line-height: 18px;
            color: #333;
            cursor: pointer;
        }
        .facility-item .facility-name.is-all {
            font-weight: 600;
        }
        .facility-item .facility-count {
            flex: 0 0 auto;
            min-width: 26px;
            padding: 1px 6px;
            border-radius: 10px;
            background: #eef1f5;
            color: #555;
            font-size: 11px;
            line-height: 16px;
            font-weight: 600;
            text-align: center;
        }
        .facility-item .form-check-input:checked + .form-check-label .facility-count {
            background: #e2ecfa;
            color: #2b5f9e;
        }

        .form-check-scrollable {
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }
        .total-info {
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 0 6px rgba(0,0,0,0.2);
            font-weight: bold;
            margin-left: 10px;
        }

        .select2-container .select2-selection--single {
            height: 45px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px;
            right: 10px;
        }

        .p-modal{
            text-align:justify;
        }
        .hospital-legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0 5px;
        }
        .hospital-legend-item img {
            width: 30px;
            height: 30px;
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
    .class-medical-classification {border: none; text-align: center; text-transform: uppercase;}
    .class-airport-category {border: none; text-transform: uppercase;}
    .class-advanced { border-bottom: 3px solid #0070c0; }
    .class-intermediate { border-bottom: 3px solid #00b050; }
    .class-basic { border-bottom: 3px solid #ffc000; }

    /* Airport layout */
    .airport-list {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 0 30px;
    }

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

     .select-input {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 8px 10px;
        background: #fff;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .select-input input {
        border: none;
        width: 100%;
        cursor: pointer;
        background: transparent;
        outline: none;
    }

    .select-dropdown {
        display: none;
        position: absolute;
        width: 100%;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-top: 3px;
        z-index: 9999;
        max-height: 250px;
        overflow: hidden;
    }

    .select-dropdown.show {
        display: block;
    }

    .dropdown-search {
        width: 100%;
        border: none;
        border-bottom: 1px solid #ddd;
        padding: 8px;
        outline: none;
    }

    #provinceList {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 180px;
        overflow-y: auto;
    }

    #provinceList li {
        padding: 5px 10px;
    }

    #provinceList li:hover {
        background: #f5f5f5;
    }

    #provinceList label {
        width: 100%;
        margin: 0;
        cursor: pointer;
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
    width: 12px;
    height: 12px;
    flex-shrink: 0;
}

.legend-grid-item small {
    text-align: left;
}

/* Keep all three legend groups aligned inside the card. */
.dashboard-legend {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    flex-wrap: wrap;
    gap: 24px;
    padding: 10px 12px;
}
.dashboard-legend .legend-section {
    flex: 0 1 auto;
    min-width: 0;
    text-align: left;
}
.dashboard-legend .legend-section-title {
    text-align: left;
    white-space: nowrap;
    margin-bottom: 3px;
}
.dashboard-legend .airport-list {
    padding: 0;
    align-items: stretch;
}
.dashboard-legend .airport-list .hospital-row {
    display: grid !important;
    grid-template-columns: repeat(3, max-content);
    gap: 0 8px;
}
.dashboard-legend .airport-list .hospital-item {
    display: contents;
}
.dashboard-legend .legend-grid-item,
.dashboard-legend .airport-list .btn,
.dashboard-legend .hospital-list .btn {
    display: inline-flex;
    align-items: center;
    justify-content: flex-start;
    gap: 4px;
    white-space: nowrap;
    text-align: left;
    min-height: 32px;
}
.dashboard-legend .legend-grid-item img,
.dashboard-legend .airport-list .btn img,
.dashboard-legend .hospital-list .btn img {
    flex: none;
    object-fit: contain;
}
.dashboard-legend .medical-classes {
    display: flex;
    gap: 0;
}
.dashboard-legend .medical-classes .class-column {
    flex: 0 0 auto;
    text-align: left;
}
.dashboard-legend .medical-classes .class-column + .class-column {
    padding-left: 10px;
}
.dashboard-legend .medical-classes .class-header {
    margin-left: -10px;
    padding-left: 10px;
}
.dashboard-legend .medical-classes .class-column:first-child .class-header {
    margin-left: 0;
    padding-left: 0;
}
.dashboard-legend .medical-classes .hospital-list {
    align-items: flex-start;
}
.dashboard-legend .police-legend .legend-grid {
    grid-template-columns: repeat(2, max-content);
}
@media (max-width: 991px) {
    .dashboard-legend { justify-content: flex-start; }
    .dashboard-legend .legend-section-title { white-space: normal; }
}

/* ===== Google Places Autocomplete Fix ===== */
.pac-container {
    z-index: 99999 !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.2) !important;
    font-family: inherit !important;
    margin-top: 2px !important;
    border: 1px solid #ddd !important;
}

.pac-item {
    padding: 6px 12px !important;
    cursor: pointer !important;
    font-size: 13px !important;
    border-top: 1px solid #f0f0f0 !important;
}

.pac-item:hover {
    background: #f0f6ff !important;
}

.pac-item-query {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #333 !important;
}

.pac-matched {
    color: #1a73e8 !important;
    font-weight: 700 !important;
}

#locationSearchMap:focus {
    outline: none !important;
    border-color: #1a73e8 !important;
    box-shadow: 0 0 0 2px rgba(26,115,232,0.2) !important;
}

/* === Info modal bertab (Polda / Polres / Polsek) ===
   Sama seperti di halaman Police. Lebarnya cukup untuk satu baris tab,
   tingginya mengikuti isi. CSS halaman ini Bootstrap 4 (AdminLTE), jadi
   lebar dialog harus di-override sendiri. */
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

/* Perbandingan struktur komando Brimob nasional dan regional */
.brimob-structure-table {
    width: 100%;
    margin: 10px 0 8px;
    border-collapse: collapse;
    color: #111;
}
.brimob-structure-table th {
    width: 50%;
    padding: 8px 12px;
    border: 1px solid #fff;
    background: #1d6687;
    color: #fff;
    font-size: 16px;
    line-height: 20px;
    text-align: left;
}
.brimob-structure-table td {
    width: 50%;
    padding: 14px 12px;
    border: 1px solid #58b8e8;
    background: #fff;
    vertical-align: top;
}
.brimob-command-flow {
    margin-bottom: 12px;
    text-align: center;
    font-size: 15px;
    line-height: 21px;
}
.brimob-command-flow strong {
    display: block;
}
.brimob-command-flow .flow-arrow {
    margin: 3px 0;
    font-size: 18px;
    line-height: 20px;
}
.brimob-structure-table .structure-description {
    margin: 0;
    text-align: justify;
}
@media (max-width: 767px) {
    .brimob-structure-table,
    .brimob-structure-table tbody,
    .brimob-structure-table tr,
    .brimob-structure-table th,
    .brimob-structure-table td {
        display: block;
        width: 100%;
    }
}

</style>

@endpush

@section('conten')

<div class="card">
    <div class="row" style="background-color: #dfeaf1;">
       <div class="col-md-9">
            <div class="dashboard-legend">

                <!-- Airport -->
                      <div class="legend-section">
                        <div class="class-header class-airport-category legend-section-title">Airfield Classification</div>
                        <div class="airport-list">
                          <div class="hospital-row" style="flex-direction: column;">
                            <!-- Airport row 1 -->
                            <div class="hospital-item">
                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level6Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:18px; height:18px;">
                                  <small>International</small>
                              </button>

                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level5Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:18px; height:18px;">
                                  <small>Domestic</small>
                              </button>

                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level4Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:18px; height:18px;">
                                  <small>Regional</small>
                              </button>
                            </div>
                            <!-- Airport row 2 -->
                            <div class="hospital-item">
                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:18px; height:18px;">
                                  <small>Civil-Military</small>
                              </button>

                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level3Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:18px; height:18px;">
                                  <small>Military</small>
                              </button>

                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level1Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:18px; height:18px;">
                                  <small>Private</small>
                              </button>
                            </div>
                          </div>

                        </div>
                      </div>

                      <!-- Medical Facility Legend -->
                      <div class="legend-section">
                        <!-- Title -->
                        <div>
                            <div class="class-header class-medical-classification legend-section-title">Medical Facility Classification</div>
                        </div>
                        <div class="medical-classes">
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
        </div>

        <div class="col-md-3">
            <div class="d-flex justify-content-end p-3">
                <div class="d-flex gap-2 mt-2">

                    <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                        <i class="bi bi-airplane fs-3"></i>
                        <small>Airports</small>
                    </a>

                    <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
                    <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                        <small>Medical</small>
                    </a>

                    <a href="{{ url('police') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('police') ? 'active' : '' }}">
                    <i class="bi bi-person-badge" style="width: 24px; height: 24px;"></i>
                        <small>Police</small>
                    </a>

                    <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees') ? 'active' : '' }}">
                    <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                        <small>Embassies</small>
                    </a>
                </div>
            </div>
        </div>
    </div>


</div>

<div id="map"></div>

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
        <p class="p-modal">Also known as private airfields or airstrips are primarily used for general and private aviation are owned by private individuals, groups, corporations, or organizations operated for their exclusive use that may include limited access for authorized personnel by the owner or manager. Owners are responsible to ensure safe operation, maintenance, repair, and control of who can use the facilities. Typically, they are not open to the public or provide scheduled commercial airline services and cater to private pilots, business aviation, and sometimes small charter operations. Services may be provided if authorized by the appropriate regulatory authority.</p>

        <p class="p-modal">A large majority of private airports are grass or dirt strip fields without services or facilities, they may feature amenities such as hangars, fueling facilities, maintenance services, and ground transportation options tailored to the needs of their owners or users. Private airports are not subject to the same level of regulatory oversight as public airports, but must still comply with applicable aviation regulations, safety standards, and environmental requirements. In the event of an emergency, landing at a private airport is authorized without any prior approval and should be done if landing anywhere else compromises the safety of the aircraft, crew, passengers, or cargo.</p>
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
            <h5 class="modal-title" id="disclaimerLabel">Combined Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Also called "joint-use airport," are used by both civilian and military aircraft, where a formal agreement exists between the military and a local government agency allowing shared access to infrastructure and facilities, typically with separate passenger terminals and designated operating areas, airspace allocation, and aircraft scheduling. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
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
        <p class="p-modal">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
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
        <p class="p-modal">A small or remote regional domestic airfield usually located in a geographically isolated area, far from major population centers, often with difficult terrain or vast distances from other airports with limited passenger traffic. May have shorter runways, basic facilities, and limited amenities, and basic infrastructure, serving primarily local communities providing access to essential services like medical transport or regional travel, rather than large-scale commercial flights.</p>
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
        <p class="p-modal">Exclusively manages flights that originate and end within the same country, does not have international customs or border control facilities. Airport often has smaller and shorter runways, suitable for smaller regional aircraft used on domestic routes, and cannot support larger haul aircraft having less developed support services. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
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
        <p class="p-modal">Meet standards set by the International Air Transport Association (IATA) and the International Civil Aviation Organization (ICAO), facilitate transnational travel managing flights between countries, have customs and border control facilities to manage passengers and cargo, and may have dedicated terminals for domestic and international flights. International airports have longer runways to accommodate larger, heavier aircraft, are often a main hub for air traffic, and can serve as a base for larger airlines. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level7Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
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
        <p class="p-modal">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd-WVlGgZFJwAtPZkbAEca2Np6OI7CBTM&libraries=places,geometry,drawing"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('click', (e) => {
    const provinceSelectInput = e.target.closest('#provinceSelect .select-input');
    const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');
    const provinceSearch = document.getElementById('provinceSearch');

    if (provinceSelectInput) {
        if (provinceDropdown) provinceDropdown.classList.toggle('show');
    } else {
        const provinceSelect = document.getElementById('provinceSelect');
        if (provinceSelect && !provinceSelect.contains(e.target) && provinceDropdown) {
            provinceDropdown.classList.remove('show');
        }
    }
}, true);

document.addEventListener('keyup', (e) => {
    if (e.target.id === 'provinceSearchInput') {
        const keyword = e.target.value.toLowerCase();
        document.querySelectorAll('#provinceList li').forEach(li => {
            const text = li.textContent.toLowerCase();
            li.style.display = text.includes(keyword) ? '' : 'none';
        });
    }
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('province-checkbox')) {
        const selected = [...document.querySelectorAll('.province-checkbox:checked')]
            .map(cb => cb.parentElement.textContent.trim());
        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) {
            if (selected.length === 0) {
                provinceSearch.value = '';
                provinceSearch.placeholder = 'Select Province';
            } else if (selected.length <= 2) {
                provinceSearch.value = selected.join(', ');
            } else {
                provinceSearch.value = selected.length + ' Province Selected';
            }
        }
    }

});
</script>

<script>    // --- Map Initialization ---
    const map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: -8.6557505239603, lng: 125.91557803754111 },
        zoom: 8,
        mapTypeId: 'roadmap',
        mapTypeControl: true,
        fullscreenControl: true,
        streetViewControl: false
    });    // --- Global States ---
    let airportMarkers = [];
    let hospitalMarkers = [];
    let policeMarkers = [];
    let embassyMarkers = [];
    const infoWindow = new google.maps.InfoWindow();
    let drawnPolygonGeoJSON = null;
    let radiusCircle = null;
    let radiusPinMarker = null;
    let lastClickedLocation = null;
    let totalHospitals = 0;
    let totalAirports = 0;
    let totalPolice = 0;
    let totalEmbassies = 0;

    // --- Directions (in-map routing) ---
    const directionsService  = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: false,
        polylineOptions: { strokeColor: '#1a73e8', strokeWeight: 5, strokeOpacity: 0.85 }
    });
    directionsRenderer.setMap(map);

    // "Clear Route" button
    const clearRouteBtn = document.createElement('div');
    clearRouteBtn.id = 'clearRouteBtn';
    clearRouteBtn.innerHTML = '✕ Clear Route';
    Object.assign(clearRouteBtn.style, {
        display: 'none',
        background: '#fff',
        border: '2px solid rgba(0,0,0,0.2)',
        borderRadius: '6px',
        padding: '6px 12px',
        fontSize: '13px',
        fontWeight: '600',
        cursor: 'pointer',
        margin: '10px',
        color: '#d32f2f',
        boxShadow: '0 2px 6px rgba(0,0,0,0.15)'
    });
    clearRouteBtn.title = 'Clear the current route';
    clearRouteBtn.addEventListener('click', () => {
        directionsRenderer.setDirections({ routes: [] });
        clearRouteBtn.style.display = 'none';
    });
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(clearRouteBtn);

    // --- Nearby Category Bar (Google Maps style) ---
    let categoryMarkers   = [];
    let activeCategoryBtn = null;

    const categoryBar = document.createElement('div');
    categoryBar.id = 'nearbyCategBar';
    Object.assign(categoryBar.style, {
        display:       'none',
        background:    'transparent',
        padding:       '8px 10px 0',
        display:       'none',
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
                // toggle off
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
        if (!lastClickedLocation) return;
        clearCategoryMarkers();

        const center  = new google.maps.LatLng(lastClickedLocation.lat, lastClickedLocation.lng);
        const service = new google.maps.places.PlacesService(map);

        // Color map per category
        const iconColors = {
            lodging:    '#1a73e8',
            restaurant: '#e53935',
            pharmacy:   '#2e7d32',
            atm:        '#f57c00',
            parking:    '#1565c0',
            cafe:       '#6d4c41',
            hospital:   '#c62828',
        };
        const color = iconColors[type] || '#555';

        function makeSvgIcon(col) {
            const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='32' height='40' viewBox='0 0 32 40'>`
                      + `<path d='M16 0C7.16 0 0 7.16 0 16c0 12 16 24 16 24S32 28 32 16C32 7.16 24.84 0 16 0z' fill='${col}'/>`
                      + `<circle cx='16' cy='16' r='7' fill='#fff'/>`
                      + `</svg>`;
            return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
        }

        const searchRadiusM  = 20000; // 20 km
        const searchRadiusKm = searchRadiusM / 1000;

        service.nearbySearch({ location: center, radius: searchRadiusM, type }, (results, status) => {
            if (status !== google.maps.places.PlacesServiceStatus.OK) {
                if (status === 'ZERO_RESULTS') {
                    alert(`No ${label.toLowerCase()} found within ${searchRadiusKm} km.`);
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
                const safeName = (place.name || '').replace(/'/g, "\\'");

                marker.addListener('click', () => {
                    infoWindow.setContent(`
                        <div style="font-size:13px;min-width:190px;">
                            <h5 style="border-bottom:1px solid #ccc;margin:0 0 6px;font-size:14px;">${place.name}</h5>
                            <div style="color:#666;font-size:12px;margin-bottom:3px;">${label}</div>
                            ${rating  ? `<div style="font-size:12px;">${rating}</div>` : ''}
                            <div style="margin-top:4px;font-size:12px;color:#555;"> ${distText} from search location</div>
                            <div style="margin-top:8px;">
                                <button onclick="showRouteOnMap(${center.lat()},${center.lng()},${destLat},${destLng},'${safeName}')"
                                        style="display:inline-flex;align-items:center;gap:5px;
                                               background:#1a73e8;color:#fff;border:none;
                                               padding:5px 12px;border-radius:6px;font-size:12px;
                                               font-weight:500;cursor:pointer;">
                                    <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                        <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                                    </svg>
                                    Get Directions
                                </button>
                            </div>
                        </div>`);
                    infoWindow.open(map, marker);
                });

                categoryMarkers.push(marker);
            });
        });
    }

    // Helper: close route panel
    function closeRoutePanel() {
        const panel = document.getElementById('routePanel');
        if (panel) panel.style.display = 'none';
        directionsRenderer.setDirections({ routes: [] });
        clearRouteBtn.style.display = 'none';
    }

    // Helper: draw route on map + show panel
    function showRouteOnMap(originLat, originLng, destLat, destLng, destName) {
        directionsService.route({
            origin: new google.maps.LatLng(originLat, originLng),
            destination: new google.maps.LatLng(destLat, destLng),
            travelMode: google.maps.TravelMode.DRIVING
        }, (result, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
                clearRouteBtn.style.display = 'inline-block';
                infoWindow.close();

                // --- Populate Route Panel ---
                const leg = result.routes[0].legs[0];
                const panel = document.getElementById('routePanel');
                document.getElementById('routePanelTitle').textContent = destName || 'Destination';
                document.getElementById('routeDistance').textContent  = leg.distance.text;
                document.getElementById('routeDuration').textContent  = leg.duration.text;

                const stepsEl = document.getElementById('routeSteps');
                stepsEl.innerHTML = leg.steps.map((step, i) => {
                    const raw = (step.html_instructions || step.instructions || '');
                    const instruction = raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                    if (!instruction) return ''; // skip steps with no text
                    const icons = {
                        'Turn left':        '↰',
                        'Turn right':       '↱',
                        'Keep left':        '↖',
                        'Keep right':       '↗',
                        'Continue':         '↑',
                        'Head':             '↑',
                        'Roundabout':       '↻',
                        'U-turn':           '⟳',
                        'Merge':            '↑',
                        'Ramp':             '↗',
                        'Destination':      '📍',
                    };
                    let icon = '•';
                    for (const [key, val] of Object.entries(icons)) {
                        if (instruction.startsWith(key)) { icon = val; break; }
                    }
                    const isLast = i === leg.steps.length - 1;
                    return `
                        <div style="display:flex;gap:10px;padding:8px 14px;
                                    border-bottom:${isLast ? 'none' : '1px solid #f0f0f0'};
                                    align-items:flex-start;">
                            <div style="min-width:22px;height:22px;background:${isLast ? '#395272' : '#e8f0fe'};
                                        border-radius:50%;display:flex;align-items:center;
                                        justify-content:center;font-size:12px;
                                        color:${isLast ? '#fff' : '#1a73e8'};flex-shrink:0;margin-top:1px;">
                                ${icon}
                            </div>
                            <div style="flex:1;">
                                <div style="font-size:12px;color:#222;line-height:1.4;">${instruction}</div>
                                <div style="font-size:11px;color:#888;margin-top:2px;">${step.distance.text}</div>
                            </div>
                        </div>`;
                }).join('');

                panel.style.display = 'flex';
            } else {
                if (status === 'ZERO_RESULTS') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Route Not Found',
                        text: 'No driving route could be found between your location and the destination. The two locations may not be connected by road.',
                        confirmButtonColor: '#1a73e8',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Directions Error',
                        text: 'Could not get directions: ' + status,
                        confirmButtonColor: '#1a73e8',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    }

    // --- Polygon Draw (Custom Point-by-Point) ---
    let isDrawingPolygon = false;
    let polygonLatLngs = [];
    let activePolygon = null;
    let activePolyline = null;
    let cursorPolyline = null;
    let startMarker = null;

    const drawButton = document.createElement('div');
    drawButton.innerHTML = '⬟';
    drawButton.style.backgroundColor = 'white';
    drawButton.style.border = '2px solid rgba(0,0,0,0.2)';
    drawButton.style.borderRadius = '4px';
    drawButton.style.width = '34px';
    drawButton.style.height = '34px';
    drawButton.style.textAlign = 'center';
    drawButton.style.lineHeight = '30px';
    drawButton.style.fontSize = '18px';
    drawButton.style.cursor = 'pointer';
    drawButton.style.margin = '10px';
    drawButton.title = 'Draw Polygon (Click point by point, click starting point to finish)';

    map.controls[google.maps.ControlPosition.LEFT_TOP].push(drawButton);

    const clearButton = document.createElement('div');
    clearButton.innerHTML = '🗑️';
    clearButton.style.backgroundColor = 'white';
    clearButton.style.border = '2px solid rgba(0,0,0,0.2)';
    clearButton.style.borderRadius = '4px';
    clearButton.style.width = '34px';
    clearButton.style.height = '34px';
    clearButton.style.textAlign = 'center';
    clearButton.style.lineHeight = '30px';
    clearButton.style.fontSize = '16px';
    clearButton.style.cursor = 'pointer';
    clearButton.style.margin = '10px 0';
    clearButton.title = 'Clear Polygon';

    map.controls[google.maps.ControlPosition.LEFT_TOP].push(clearButton);

    drawButton.addEventListener('click', () => {
        isDrawingPolygon = !isDrawingPolygon;
        if (isDrawingPolygon) {
            map.setOptions({ draggable: false });
            drawButton.style.backgroundColor = '#ccc';
            map.getDiv().style.cursor = 'crosshair';
            polygonLatLngs = [];
            if (activePolygon) activePolygon.setMap(null);
            if (activePolyline) activePolyline.setMap(null);
            if (cursorPolyline) cursorPolyline.setMap(null);
            if (startMarker) startMarker.setMap(null);
            activePolygon = null;
            activePolyline = new google.maps.Polyline({
                path: polygonLatLngs,
                strokeColor: '#0000FF',
                strokeOpacity: 0.8,
                strokeWeight: 3,
                clickable: false,
                map: map
            });
            cursorPolyline = new google.maps.Polyline({
                path: [],
                strokeColor: '#0000FF',
                strokeOpacity: 0.5,
                strokeWeight: 3,
                clickable: false,
                map: map
            });
            startMarker = null;
            drawnPolygonGeoJSON = null;
        } else {
            finishPolygon();
        }
    });

    map.addListener('click', (e) => {
        if (!isDrawingPolygon) return;
        polygonLatLngs.push(e.latLng);
        activePolyline.setPath(polygonLatLngs);

        if (polygonLatLngs.length === 1) {
            startMarker = new google.maps.Marker({
                position: e.latLng,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 6,
                    fillColor: '#FFFFFF',
                    fillOpacity: 1,
                    strokeColor: '#0000FF',
                    strokeWeight: 2,
                },
                zIndex: 999
            });
            startMarker.addListener('click', () => {
                if (isDrawingPolygon) finishPolygon();
            });
        }
    });

    map.addListener('mousemove', (e) => {
        if (!isDrawingPolygon || polygonLatLngs.length === 0) return;
        const lastPoint = polygonLatLngs[polygonLatLngs.length - 1];
        cursorPolyline.setPath([lastPoint, e.latLng]);
    });

    map.addListener('rightclick', () => {
        if (isDrawingPolygon) finishPolygon();
    });

    async function finishPolygon() {
        if (!isDrawingPolygon) return;
        isDrawingPolygon = false;
        map.setOptions({ draggable: true });
        drawButton.style.backgroundColor = 'white';
        map.getDiv().style.cursor = '';
        if (cursorPolyline) cursorPolyline.setMap(null);
        if (startMarker) startMarker.setMap(null);

        if (polygonLatLngs.length > 2) {
            if (activePolyline) activePolyline.setMap(null);
            activePolygon = new google.maps.Polygon({
                paths: polygonLatLngs,
                strokeColor: '#0000FF',
                strokeOpacity: 0.8,
                strokeWeight: 3,
                fillColor: '#0000FF',
                fillOpacity: 0.2,
                editable: true,
                map: map
            });

            const coordinates = polygonLatLngs.map(p => [p.lng(), p.lat()]);
            coordinates.push([polygonLatLngs[0].lng(), polygonLatLngs[0].lat()]); // Close polygon

            drawnPolygonGeoJSON = {
                type: "Feature",
                geometry: { type: "Polygon", coordinates: [coordinates] },
                properties: {}
            };

            const updatePolygonFilter = async () => {
                if (!activePolygon) return;
                const path = activePolygon.getPath();
                if (path.getLength() > 2) {
                    const newCoords = [];
                    for (let i = 0; i < path.getLength(); i++) {
                        const xy = path.getAt(i);
                        newCoords.push([xy.lng(), xy.lat()]);
                    }
                    newCoords.push([path.getAt(0).lng(), path.getAt(0).lat()]);
                    drawnPolygonGeoJSON.geometry.coordinates = [newCoords];
                    await refreshCurrentFilters();
                }
            };

            google.maps.event.addListener(activePolygon.getPath(), 'set_at', updatePolygonFilter);
            google.maps.event.addListener(activePolygon.getPath(), 'insert_at', updatePolygonFilter);
            google.maps.event.addListener(activePolygon.getPath(), 'remove_at', updatePolygonFilter);

            await refreshCurrentFilters();
        } else {
            if (activePolyline) activePolyline.setMap(null);
            activePolyline = null;
            activePolygon = null;
            drawnPolygonGeoJSON = null;
        }
    }

    clearButton.addEventListener('click', async () => {
        if (activePolygon) activePolygon.setMap(null);
        if (activePolyline) activePolyline.setMap(null);
        if (cursorPolyline) cursorPolyline.setMap(null);
        if (startMarker) startMarker.setMap(null);
        activePolygon = null;
        activePolyline = null;
        cursorPolyline = null;
        startMarker = null;
        polygonLatLngs = [];
        drawnPolygonGeoJSON = null;
        isDrawingPolygon = false;
        map.setOptions({ draggable: true });
        drawButton.style.backgroundColor = 'white';
        map.getDiv().style.cursor = '';
        await refreshCurrentFilters();
    });    // --- Update Radius ---
    function updateRadiusCircleAndPin(radius = 0) {
        if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }

        if (radius > 0 && lastClickedLocation) {
            radiusCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.2,
                map: map,
                center: lastClickedLocation,
                radius: radius * 1000
            });
        }
    }

    // Red pin marker for searched location (separate from radius circle)
    function placeLocationPin(location, label) {
        if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
        radiusPinMarker = new google.maps.Marker({
            position: location,
            map: map,
            title: label || 'Selected Location',
            icon: {
                url: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                scaledSize: new google.maps.Size(25, 41)
            },
            zIndex: 9999,
            animation: google.maps.Animation.DROP
        });
    }

    // Enable/disable radius section based on whether location is set
    function setRadiusSectionEnabled(enabled) {
        const section = document.getElementById('radiusSection');
        if (!section) return;
        section.style.opacity = enabled ? '1' : '0.4';
        section.style.pointerEvents = enabled ? 'auto' : 'none';
    }

    // --- Init Location Search — Google Places Autocomplete ---
    // .pac-container is repositioned to position:fixed via MutationObserver
    // to bypass Google Maps container overflow:hidden clipping.
    function initLocationSearch() {
        const input = document.getElementById('locationSearchMap');
        if (!input) {
            setTimeout(initLocationSearch, 300);
            return;
        }

        const clearBtn = document.getElementById('locationSearchClear');

        // ── 1. Create Google Places Autocomplete ──────────────────────────────
        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode', 'establishment'],
            fields: ['geometry', 'name', 'formatted_address']
        });

        // ── 2. Fix .pac-container position to avoid map overflow:hidden ───────
        // Google appends .pac-container to <body> but uses position:absolute,
        // calculated from the element's document offset. Because the map container
        // applies its own offset context, the top/left values are wrong.
        // We override with position:fixed + getBoundingClientRect().
        let pacContainer = null;

        function fixPacPosition() {
            if (!pacContainer) return;
            const rect = input.getBoundingClientRect();
            pacContainer.style.position   = 'fixed';
            pacContainer.style.zIndex     = '2147483647';
            pacContainer.style.top        = (rect.bottom + 2) + 'px';
            pacContainer.style.left       = rect.left + 'px';
            pacContainer.style.width      = rect.width + 'px';
            pacContainer.style.borderRadius = '0 0 8px 8px';
            pacContainer.style.boxShadow  = '0 8px 24px rgba(0,0,0,0.2)';
            pacContainer.style.fontFamily = 'inherit';
        }

        // Watch for Google to inject .pac-container into <body>
        const observer = new MutationObserver(() => {
            if (!pacContainer) {
                pacContainer = document.querySelector('.pac-container');
                if (pacContainer) {
                    fixPacPosition();
                    // Re-fix on every style mutation (Google repositions it on scroll etc.)
                    new MutationObserver(fixPacPosition).observe(
                        pacContainer, { attributes: true, attributeFilter: ['style'] }
                    );
                }
            }
        });
        observer.observe(document.body, { childList: true, subtree: false });

        // Keep in sync with input position on scroll / resize
        window.addEventListener('scroll', fixPacPosition, true);
        window.addEventListener('resize', fixPacPosition);
        input.addEventListener('focus',  fixPacPosition);
        input.addEventListener('input',  fixPacPosition);

        // ── 3. Prevent map from capturing keyboard input ───────────────────────
        google.maps.event.addDomListener(input, 'keydown',   e => e.stopPropagation());
        google.maps.event.addDomListener(input, 'mousedown', e => e.stopPropagation());

        // ── 4. Focus styling ───────────────────────────────────────────────────
        input.addEventListener('focus', () => {
            input.style.borderColor = '#1a73e8';
            input.style.boxShadow   = '0 0 0 3px rgba(26,115,232,0.15)';
        });
        input.addEventListener('blur', () => {
            input.style.borderColor = '#ddd';
            input.style.boxShadow   = 'none';
        });

        // Show/hide × button
        input.addEventListener('input', () => {
            if (clearBtn) clearBtn.style.display = input.value.length ? 'inline' : 'none';
        });

        // ── 5. Handle place selection ─────────────────────────────────────────
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) return;

            const loc = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            };
            lastClickedLocation = loc;

            map.panTo(loc);
            map.setZoom(10);

            const label = place.name || place.formatted_address || 'Location';
            placeLocationPin(loc, label);

            if (clearBtn) clearBtn.style.display = 'inline';

            const badge    = document.getElementById('locationFoundBadge');
            const badgeName = document.getElementById('locationFoundName');
            if (badge)     badge.style.display = 'block';
            if (badgeName) badgeName.textContent = label;

            setRadiusSectionEnabled(true);
            const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
            updateRadiusCircleAndPin(radius);
            refreshCurrentFilters();

            // Show category bar
            categoryBar.style.display = 'flex';
        });

        // ── 6. Clear button ───────────────────────────────────────────────────
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                input.value = '';
                clearBtn.style.display = 'none';
                if (pacContainer) pacContainer.style.display = 'none';

                const badge = document.getElementById('locationFoundBadge');
                if (badge) badge.style.display = 'none';

                if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
                if (radiusCircle)    { radiusCircle.setMap(null);    radiusCircle    = null; }
                lastClickedLocation = null;

                // Hide category bar & clear category markers
                categoryBar.style.display = 'none';
                clearCategoryMarkers();
                if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

                setRadiusSectionEnabled(false);
                const rEl    = document.getElementById('radiusRangeMap');
                const rValEl = document.getElementById('radiusValueMap');
                if (rEl)    rEl.value          = 0;
                if (rValEl) rValEl.textContent = '0';

                refreshCurrentFilters();
                input.focus();
            });
        }
    }

    // --- Fetch Data ---
    async function fetchData(url, filters = {}) {
        const params = new URLSearchParams();
        Object.entries(filters).forEach(([k, v]) => {
            if (Array.isArray(v)) v.forEach(x => params.append(`${k}[]`, x));
            else if (v !== '' && v != null) params.append(k, v);
        });
        if (drawnPolygonGeoJSON) params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));
        //  console.log(url + '?' + params.toString());

        try {
            const res = await fetch(`${url}?${params.toString()}`);
            return res.ok ? await res.json() : [];
        } catch (e) {
            console.error(`Error fetching ${url}:`, e);
            return [];
        }
    }    // --- Add Markers ---
    function clearMarkers(markersArray) {
        if (!markersArray) return;
        markersArray.forEach(m => m.setMap(null));
        markersArray.length = 0;
    }

    function addMarkers(data, markersArray, defaultIconUrl) {
        clearMarkers(markersArray);
        data.forEach(item => {
            if (!item || !item.latitude || !item.longitude) return;

            let iconSize = new google.maps.Size(24, 24);

            // Police icon lebih kecil
            if (item.name_police) {
                iconSize = new google.maps.Size(12, 12);
            }

            const iconUrl = item.icon || defaultIconUrl || 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png';

            const marker = new google.maps.Marker({
                position: { lat: parseFloat(item.latitude), lng: parseFloat(item.longitude) },
                map: map,
                icon: {
                    url: iconUrl,
                    scaledSize: iconSize
                }
            });

            let itemName = '', detailUrl = '', popupContent = '';

            if (item.airport_name) {
                itemName = item.airport_name;
                detailUrl = `/airports/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;"><a href="${detailUrl}" style="color:inherit;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#1a73e8'" onmouseout="this.style.color='inherit'">${itemName}</a></h5>
                    <strong>Classification:</strong> ${item.category || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city_name ? ', ' + item.city_name : ''}
                        ${item.province_name ? ', ' + item.province_name : ''}<br>
                    <strong>Website:</strong> ${item.website || 'N/A'} <br>
                `;
            } else if (item.name) {
                itemName = item.name;
                detailUrl = `/hospitals/${item.id}`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;"><a href="${detailUrl}" style="color:inherit;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#1a73e8'" onmouseout="this.style.color='inherit'">${itemName}</a></h5>
                    <strong>Global Classification:</strong> ${item.facility_category || 'N/A'}<br>
                    <strong>Country Classification:</strong> ${item.facility_level || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city ? ', ' + item.city : ''}
                        ${item.provinces_region ? ', ' + item.provinces_region : ''}<br>
                `;
            } else if (item.name_police) {
                itemName = item.name_police;
                detailUrl = `/police/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;"><a href="${detailUrl}" style="color:inherit;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#1a73e8'" onmouseout="this.style.color='inherit'">${itemName}</a></h5>
                    <strong>Category:</strong> ${item.category || 'N/A'}<br>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city ? ', ' + item.city : ''}
                        ${item.provinces_region ? ', ' + item.provinces_region : ''}<br>
                    <strong>Phone:</strong> ${item.telephone || 'N/A'}<br>
                    <strong>Fax:</strong> ${item.fax || 'N/A'}<br>
                    <strong>Email:</strong> ${item.email || 'N/A'}<br>
                    <strong>Website:</strong> ${item.website || 'N/A'}<br>
                `;
            }
            else if (item.name_embassiees) {
                itemName = item.name_embassiees;
                detailUrl = `/embassiees/${item.id}/detail`;
                popupContent = `
                    <h5 style="border-bottom:1px solid #cccccc;"><a href="${detailUrl}" style="color:inherit;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#1a73e8'" onmouseout="this.style.color='inherit'">${itemName}</a></h5>
                    <strong>Address:</strong>
                        ${item.address || 'N/A'}
                        ${item.city ? ', ' + item.city : ''}
                        ${item.provinces_region ? ', ' + item.provinces_region : ''}<br>
                    <strong>Phone:</strong> ${item.telephone || 'N/A'}<br>
                    <strong>Fax:</strong> ${item.fax || 'N/A'}<br>
                    <strong>Email:</strong> ${item.email || 'N/A'}<br>
                    <strong>Website:</strong> ${item.website || 'N/A'}<br>
                `;
            }



            marker.addListener('click', () => {
                const destLat = parseFloat(item.latitude);
                const destLng = parseFloat(item.longitude);

                let directionsBtn = '';
                if (lastClickedLocation && !isNaN(destLat) && !isNaN(destLng)) {
                    const oLat = lastClickedLocation.lat;
                    const oLng = lastClickedLocation.lng;
                    directionsBtn = `
                        <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;display:flex;gap:6px;flex-wrap:wrap;">
                            <button onclick="showRouteOnMap(${oLat},${oLng},${destLat},${destLng},'${(itemName||'').replace(/'/g,"\\'")}')"
                               style="display:inline-flex;align-items:center;gap:5px;
                                      background:#1a73e8;color:#fff;border:none;
                                      padding:5px 12px;border-radius:6px;font-size:12px;
                                      font-weight:500;cursor:pointer;">
                                <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                    <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                                </svg>
                                Get Directions
                            </button>
                            <a href="${detailUrl}"
                               style="display:inline-flex;align-items:center;gap:5px;
                                      background:#395272;color:#fff;text-decoration:none;
                                      padding:5px 12px;border-radius:6px;font-size:12px;
                                      font-weight:500;"
                               onmouseover="this.style.background='#5686c3'"
                               onmouseout="this.style.background='#395272'">
                                <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                    <circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/>
                                </svg>
                                Read More
                            </a>
                        </div>`;
                } else if (detailUrl) {
                    directionsBtn = `
                        <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;">
                            <a href="${detailUrl}"
                               style="display:inline-flex;align-items:center;gap:5px;
                                      background:#395272;color:#fff;text-decoration:none;
                                      padding:5px 12px;border-radius:6px;font-size:12px;
                                      font-weight:500;"
                               onmouseover="this.style.background='#5686c3'"
                               onmouseout="this.style.background='#395272'">
                                <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                    <circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/>
                                </svg>
                                Read More
                            </a>
                        </div>`;
                }

                infoWindow.setContent(`<div style="font-size:13px; min-width: 200px;">${popupContent}${directionsBtn}</div>`);
                infoWindow.open(map, marker);
            });

            markersArray.push(marker);
        });
    }

    // --- Apply Filters ---
    async function applyFiltersWithMapControl(
        facilities = [],
        hospitalLevels = [],
        airportClasses = [],
        provinces = [],
        radius = 0,
        airportName = '',
        hospitalName = ''
    ) {
        let common = { provinces };
        if (radius > 0 && lastClickedLocation) {
            common.radius = radius;
            common.center_lat = lastClickedLocation.lat;
            common.center_lng = lastClickedLocation.lng;
        }

        totalHospitals = 0;
        totalAirports = 0;
        totalPolice = 0;
        totalEmbassies = 0;

        // hanya facility yang dicentang yang ditampilkan
        // (checkbox "All" mencentang semuanya sekaligus)
        const showHospital = facilities.includes('hospital');
        const showAirport = facilities.includes('airport');
        const showPolice = facilities.includes('police');
        const showEmbassy = facilities.includes('embassy');

         // === HOSPITALS ===
        if (showHospital) {
             const result = await fetchData('/api/hospital', {
                ...common,
                name: hospitalName,
                category: hospitalLevels
            });

            const hospitals = Array.isArray(result) ? result : result.hospitals || [];
            addMarkers(hospitals, hospitalMarkers, null);

            totalHospitals = hospitals.length;
        } else {
            clearMarkers(hospitalMarkers);
        }

        // === AIRPORTS ===
       if (showAirport) {

            const airportResponse = await fetchData('/api/airports', {
                ...common,
                name: airportName
            });

            const airports = Array.isArray(airportResponse)
                    ? airportResponse
                    : airportResponse.airports || [];
            const categoryCounts = airportResponse.categoryCounts || {};

            const filteredAirports = airports.filter(a => {

                if (airportClasses.length === 0) {
                    return true;
                }

                if (!a.category) {
                    return false;
                }

                const dbCategories = a.category
                    .split(',')
                    .map(c => c.trim().toLowerCase());

                return airportClasses.some(sel =>
                    dbCategories.includes(sel.toLowerCase())
                );
            });

            addMarkers(
                filteredAirports,
                airportMarkers,
                'https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png'
            );

            totalAirports = filteredAirports.length;
        }else {
            clearMarkers(airportMarkers);
        }

        // === POLICE ===
       if (showPolice) {

            const result = await fetchData('/api/polices', {
                ...common
            });

            const police = result.polices || [];
            const categoryCounts = result.categoryCounts || {};

            addMarkers(
                police,
                policeMarkers,
                null
            );

            totalPolice = police.length;

            Object.keys(categoryCounts).forEach(cat => {

                const id = cat.replace(/[^a-zA-Z0-9]/g, '-');

                const el = document.getElementById(`count-${id}`);

                if (el) {
                    el.textContent = categoryCounts[cat];
                }
            });
        } else {
            clearMarkers(policeMarkers);
        }

        // === EMBASSY ===
        if (showEmbassy) {

            const embassies = await fetchData('/api/embassy', {
                ...common
            });

            addMarkers(
                embassies,
                embassyMarkers,
                '/images/embassy-icon-new.png'
            );

            totalEmbassies = embassies.length;

        } else {
            clearMarkers(embassyMarkers);
        }

        updateRadiusCircleAndPin(radius);
        updateTotalCountDisplay();
    }

    function updateTotalCountDisplay() {
        // Panel filter di-attach oleh Google Maps secara async,
        // jadi elemen counter bisa belum ada saat load pertama.
        const setCount = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        };

        setCount('airportCount', totalAirports);
        setCount('hospitalCount', totalHospitals);
        setCount('policeCount', totalPolice);
        setCount('embassyCount', totalEmbassies);
    }    // === COMBINED PANEL ===
    const combinedPanelDiv = document.createElement('div');
    combinedPanelDiv.id = 'combinedPanelDiv';
    Object.assign(combinedPanelDiv.style, {
        background: 'white',
        borderRadius: '8px',
        boxShadow: '0 2px 6px rgba(0,0,0,0.2)',
        minWidth: '260px',
        maxWidth: '290px',
        overflow: 'visible',
        margin: '10px'
    });

    combinedPanelDiv.innerHTML = `
        <button style="background:#007bff;color:white;border:none;width:100%;padding:8px;border-radius:8px 8px 0 0;font-weight:600;letter-spacing:0.3px;">Filter &amp; Radius</button>

        <!-- Search Location - NOT inside scrollable div so dropdown is never clipped -->
        <div id="searchSection" style="padding:10px 10px 6px 10px;background:white;position:relative;">
            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;"> Search Location</strong>
            <div style="position:relative;margin-top:5px;">
                <input
                    type="text"
                    id="locationSearchMap"
                    placeholder="Search Location..."
                    autocomplete="off"
                    style="width:100%;padding:7px 30px 7px 9px;border:1.5px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;"
                >
                <span id="locationSearchClear" title="Clear"
                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:15px;color:#aaa;display:none;">&times;</span>
                <!-- Autocomplete dropdown - inside input wrapper so position relative works correctly -->
                <div id="locationAutocompleteList"
                    style="display:none;position:absolute;left:0;right:0;top:100%;margin-top:2px;background:white;border:1px solid #ddd;border-radius:6px;box-shadow:0 4px 16px rgba(0,0,0,0.18);z-index:999999;max-height:220px;overflow-y:auto;"
                ></div>
            </div>
            <div id="locationFoundBadge" style="display:none;margin-top:6px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:5px;padding:4px 8px;font-size:12px;color:#2e7d32;">
                &#128204; <span id="locationFoundName"></span>
            </div>
        </div>

        <!-- Radius - also outside scrollable, enabled after location selected -->
        <div id="radiusSection" style="padding:0 10px 0 10px;opacity:0.4;pointer-events:none;transition:opacity 0.3s;">
            <hr style="margin:8px 0;">
            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">&#11096; Radius: <span id="radiusValueMap">0</span> km</strong>
            <input type="range" id="radiusRangeMap" min="0" max="500" value="0" style="width:100%;margin:4px 0;">
            <div style="display:flex;justify-content:space-between;font-size:11px;color:#888;margin-bottom:5px;">
                <span>0</span><span>250 km</span><span>500 km</span>
            </div>
            <div style="display:flex;gap:5px;margin-bottom:6px;">
                <button id="applyRadiusMap" class="btn btn-sm btn-primary flex-fill">Apply</button>
                <button id="resetRadiusMap" class="btn btn-sm btn-danger flex-fill">Reset</button>
            </div>
        </div>

        <!-- Scrollable filters below -->
        <div id="filterPanel" style="padding:0 10px 10px 10px;max-height:52vh;overflow-y:auto;border-top:1px solid #eee;">
            <div style="padding-top:8px;">
            <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Facilities</strong>

                    <div class="facility-list">

                        <div class="facility-item">
                            <input class="form-check-input facility-checkbox" type="checkbox" value="airport" id="facilityAirport" checked>
                            <label class="form-check-label" for="facilityAirport">
                                <span class="facility-name">Aviation</span>
                                <span class="facility-count" id="airportCount">0</span>
                            </label>
                        </div>

                        <div class="facility-item">
                            <input class="form-check-input facility-checkbox" type="checkbox" value="hospital" id="facilityHospital">
                            <label class="form-check-label" for="facilityHospital">
                                <span class="facility-name">Medical</span>
                                <span class="facility-count" id="hospitalCount">0</span>
                            </label>
                        </div>

                        <div class="facility-item">
                            <input class="form-check-input facility-checkbox" type="checkbox" value="police" id="facilityPolice">
                            <label class="form-check-label" for="facilityPolice">
                                <span class="facility-name">Police</span>
                                <span class="facility-count" id="policeCount">0</span>
                            </label>
                        </div>

                        <div class="facility-item">
                            <input class="form-check-input facility-checkbox" type="checkbox" value="embassy" id="facilityEmbassy">
                            <label class="form-check-label" for="facilityEmbassy">
                                <span class="facility-name">Embassies</span>
                                <span class="facility-count" id="embassyCount">0</span>
                            </label>
                        </div>

                        <div class="facility-item">
                            <input class="form-check-input" type="checkbox" value="all" id="facilityAll">
                            <label class="form-check-label" for="facilityAll">
                                <span class="facility-name is-all">All / Clear All</span>
                            </label>
                        </div>

                    </div>

                    <hr>
                    <div class="filter-box" id="provinceSelect">
                        <label class="filter-label">
                            Province
                        </label>

                        <div class="select-input">
                            <input
                                type="text"
                                id="provinceSearch"
                                placeholder="Select Province"
                                readonly
                            >
                            <i class="bi bi-chevron-down"></i>
                        </div>

                        <div class="select-dropdown">
                            <input
                                type="text"
                                class="dropdown-search"
                                id="provinceSearchInput"
                                placeholder="Search Province..."
                            >

                            <ul id="provinceList">
                                @foreach($provinces as $province)
                                <li>
                                    <label>
                                        <input
                                            type="checkbox"
                                            class="province-checkbox"
                                            value="{{ $province->id }}"
                                        >
                                        {{ $province->provinces_region }}
                                    </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <hr>
                    <button id="resetMapFilter"
                            class="btn btn-sm btn-secondary w-100"
                            style="margin-top:auto;">
                        Reset All
                    </button>
                    <div id="totalCountDisplay" style="margin-top:8px;text-align:center;font-size:13px;"></div>
                </div>
            </div>`;            google.maps.event.addDomListener(combinedPanelDiv, 'click', e => e.stopPropagation());
            google.maps.event.addDomListener(combinedPanelDiv, 'dblclick', e => e.stopPropagation());
            google.maps.event.addDomListener(combinedPanelDiv, 'mousedown', e => e.stopPropagation());
            google.maps.event.addDomListener(combinedPanelDiv, 'touchstart', e => e.stopPropagation());
            google.maps.event.addDomListener(combinedPanelDiv, 'wheel', e => e.stopPropagation());
            map.controls[google.maps.ControlPosition.RIGHT_TOP].push(combinedPanelDiv);

    // === FACILITIES "ALL" CHECKBOX SYNC ===
    // Didaftarkan pada fase capture SEBELUM listener filter di bawah,
    // supaya state checkbox sudah tersinkron saat filter dibaca.
    function syncFacilityAllCheckbox() {
        const all = document.getElementById('facilityAll');
        if (!all) return;
        const boxes = [...document.querySelectorAll('.facility-checkbox')];
        all.checked = boxes.length > 0 && boxes.every(cb => cb.checked);
    }

    document.addEventListener('change', e => {
        if (!e.target) return;

        if (e.target.id === 'facilityAll') {
            document.querySelectorAll('.facility-checkbox').forEach(cb => {
                cb.checked = e.target.checked;
            });
            return;
        }

        if (e.target.classList && e.target.classList.contains('facility-checkbox')) {
            syncFacilityAllCheckbox();
        }
    }, true);

    // === INIT SELECT2 ===
    setTimeout(() => {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select-search-airport').select2({ placeholder: 'Select Airport', width: '100%' });
            $('.select-search-hospital').select2({ placeholder: 'Select Hospital', width: '100%' });
        }
    }, 300);

    function getCurrentFiltersFromUI() {
        const facilities = [...document.querySelectorAll('.facility-checkbox:checked')].map(el => el.value);
        const hLevels = [...document.querySelectorAll('input[name="hospitalLevel"]:checked')].map(e => e.value);
        const aClasses = [...document.querySelectorAll('input[name="airportClass"]:checked')].map(e => e.value);
        const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
        const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
        // untuk select2, .value akan tetap bekerja because Select2 keeps value in the <select>
        const airportName = document.getElementById('airport_name_map')?.value || '';
        const hospitalName = document.getElementById('hospital_name_map')?.value || '';
        return { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName };
    }

    async function refreshCurrentFilters() {
        const {
            facilities,
            hLevels,
            aClasses,
            provs,
            radius,
            airportName,
            hospitalName
        } = getCurrentFiltersFromUI();

        await applyFiltersWithMapControl(
            facilities,
            hLevels,
            aClasses,
            provs,
            radius,
            airportName,
            hospitalName
        );
    }

    // === Event Logic ===
    document.addEventListener('change', async e => {
        const facilities = [...document.querySelectorAll('.facility-checkbox:checked')].map(el => el.value);
        const hLevels = [...document.querySelectorAll('input[name="hospitalLevel"]:checked')].map(e => e.value);
        const aClasses = [...document.querySelectorAll('input[name="airportClass"]:checked')].map(e => e.value);
        const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
        const radius = parseInt(document.getElementById('radiusRangeMap').value || 0);
        const airportName = document.getElementById('airport_name_map')?.value || '';
        const hospitalName = document.getElementById('hospital_name_map')?.value || '';

        await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
    }, true);

    // === INPUT: update tampilan radius saat slider digeser (live) ===
document.addEventListener('input', (e) => {
    if (e.target && e.target.id === 'radiusRangeMap') {
        const r = parseInt(e.target.value || 0);
        const el = document.getElementById('radiusValueMap');
        if (el) el.textContent = r;
        // hanya update tampilan lingkaran saja (belum apply ke filter)
        updateRadiusCircleAndPin(r);
    }
}, true);

// === CLICK: apply / reset radius dan reset all ===
// Menggunakan event capturing (true) agar tidak diblok oleh stopPropagation pada map control
document.addEventListener('click', async (e) => {
    if (!e.target) return;

    // APPLY RADIUS => ambil filter sekarang lalu panggil applyFiltersWithMapControl dengan radius
    if (e.target.id === 'applyRadiusMap') {
        const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
        if (radius > 0 && !lastClickedLocation) {
            alert('Cari lokasi terlebih dahulu menggunakan kolom "Search Location" sebelum menggunakan filter radius.');
            return;
        }
        await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        return;
    }

    // RESET RADIUS (hanya reset radius visual & reapply tanpa radius)
    if (e.target.id === 'resetRadiusMap') {
        const rEl = document.getElementById('radiusRangeMap');
        const rValEl = document.getElementById('radiusValueMap');
        if (rEl) rEl.value = 0;
        if (rValEl) rValEl.textContent = '0';

        if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }
        if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
        lastClickedLocation = null;

        const { facilities, hLevels, aClasses, provs, airportName, hospitalName } = getCurrentFiltersFromUI();
        await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, 0, airportName, hospitalName);
        return;
    }

    // RESET ALL FILTERS (tombol Reset All)
    if (e.target.id === 'resetMapFilter') {
        // 1) UI reset (default: hanya Aviation yang aktif)
        document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => { cb.checked = false; });
        const defaultFacility = document.getElementById('facilityAirport');
        if (defaultFacility) defaultFacility.checked = true;
        syncFacilityAllCheckbox();
        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) provinceSearch.value = '';
        const provinceSearchInput = document.getElementById('provinceSearchInput');
        if (provinceSearchInput) provinceSearchInput.value = '';
        document.querySelectorAll('#provinceList li').forEach(li => { li.style.display = ''; });

        // sembunyikan sub-panels
        const af = document.getElementById('airportFilter');
        const hf = document.getElementById('hospitalFilter');
        if (af) af.style.display = 'none';
        if (hf) hf.style.display = 'none';

        // 2) Reset Select2 (jika ada)
        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            $('.select-search-airport').each(function () { $(this).val(null).trigger('change'); });
            $('.select-search-hospital').each(function () { $(this).val(null).trigger('change'); });
        } else {
            const airportSel = document.getElementById('airport_name_map');
            const hospitalSel = document.getElementById('hospital_name_map');
            if (airportSel) airportSel.value = '';
            if (hospitalSel) hospitalSel.value = '';
        }

        // 3) Reset radius visual & location search
        const radiusRange = document.getElementById('radiusRangeMap');
        const radiusValue = document.getElementById('radiusValueMap');
        if (radiusRange) radiusRange.value = 0;
        if (radiusValue) radiusValue.textContent = '0';
        if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }
        if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
        lastClickedLocation = null;

        const locInput = document.getElementById('locationSearchMap');
        const locClear = document.getElementById('locationSearchClear');
        const locBadge = document.getElementById('locationFoundBadge');
        if (locInput) locInput.value = '';
        if (locClear) locClear.style.display = 'none';
        if (locBadge) locBadge.style.display = 'none';

        const fixedDrop = document.getElementById('locationDropdownFixed');
        if (fixedDrop) fixedDrop.style.display = 'none';
        setRadiusSectionEnabled(false);

        // 4) Remove drawn polygon and layers
        if (activePolygon) activePolygon.setMap(null);
        if (activePolyline) activePolyline.setMap(null);
        if (cursorPolyline) cursorPolyline.setMap(null);
        if (startMarker) startMarker.setMap(null);
        activePolygon = null;
        activePolyline = null;
        cursorPolyline = null;
        startMarker = null;
        polygonLatLngs = [];
        drawnPolygonGeoJSON = null;

        // 5) Clear markers and counters
        if (airportMarkers) clearMarkers(airportMarkers);
        if (hospitalMarkers) clearMarkers(hospitalMarkers);
        if (policeMarkers) clearMarkers(policeMarkers);
        if (embassyMarkers) clearMarkers(embassyMarkers);
        totalAirports = 0;
        totalHospitals = 0;
        totalPolice = 0;
        totalEmbassies = 0;
        updateTotalCountDisplay();

        // 6) Re-fetch data sesuai default (Aviation)
        await applyFiltersWithMapControl(['airport'], [], [], [], 0, '', '');

        e.stopPropagation();
        e.preventDefault();
        return;
    }
}, true);

// === LISTEN TO CHANGE on filter inputs (kategori/provinsi/select nama) ===
// Ini memastikan ketika user change checkbox / select2, filter langsung ter-apply
function bindFilterChangeAutoApply() {
    // checkbox change
    document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(el => {
        el.addEventListener('change', async () => {
            const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
            await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        });
    });

    // select2 change (nama)
    // if Select2 is used, listen with jQuery; otherwise plain change event above covers plain <select>
    if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
        $(document).on('change', '#airport_name_map, #hospital_name_map', async function () {
            const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
            await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        });
    } else {
        document.getElementById('airport_name_map')?.addEventListener('change', async () => {
            const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
            await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        });
        document.getElementById('hospital_name_map')?.addEventListener('change', async () => {
            const { facilities, hLevels, aClasses, provs, radius, airportName, hospitalName } = getCurrentFiltersFromUI();
            await applyFiltersWithMapControl(facilities, hLevels, aClasses, provs, radius, airportName, hospitalName);
        });
    }
}

// call binding after panel is rendered
setTimeout(() => {
    bindFilterChangeAutoApply();
    initLocationSearch();
}, 350);

    // --- Initial Load ---
    // Tunggu sampai panel filter benar-benar ter-attach ke DOM oleh Google Maps,
    // supaya default checkbox (Aviation) terbaca oleh getCurrentFiltersFromUI().
    (function initialLoad() {
        if (!document.getElementById('facilityAirport')) {
            setTimeout(initialLoad, 100);
            return;
        }
        refreshCurrentFilters();
    })();
</script>

@endpush
