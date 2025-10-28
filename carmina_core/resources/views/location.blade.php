@extends('layouts.app')

@section('content')
@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif
<div class="container-fluid">
    <div class="row">
        <div class="col-12 position-relative p-0 d-flex justify-content-end flex-column">
            <div id="message"><span class="d-block d-md-none message-slogan">Nuestros productos, siempre cerca de
                    ti.</span></div>
            <div id="familias" class="d-none d-md-flex">
                <span class="familia" id="f_Bovino">Bovino</span>
                <span class="familia" id="f_Elaborados">Elaborados</span>
                <span class="familia" id="f_Porcino">Porcino</span>
                <span class="familia" id="f_Avícola">Avícola</span>
                <span class="familia" id="f_Otros">Otros</span>
            </div>
            <div id="fam_mob" class="d-block d-md-none">
                <div class="multiselect-container">
                    <div class="multiselect" onclick="toggleDropdown()">Filtar por &#9660;</div>
                    <div class="multiselect-options">
                        <label>
                            <input type="checkbox" value="Bovino"> Bovino
                        </label>
                        <label>
                            <input type="checkbox" value="Elaborados"> Elaborados
                        </label>
                        <label>
                            <input type="checkbox" value="Porcino"> Porcino
                        </label>
                        <label>
                            <input type="checkbox" value="Avícola"> Avícola
                        </label>
                        <label>
                            <input type="checkbox" value="Otros"> Otros
                        </label>
                    </div>
                </div>
            </div>
            <div id="map"></div>
            <button class="openbtn position-absolute" onclick="openNav()">
                <svg viewBox="-10 -10 120 120" width="16">
                    <path d="M 50,0 L 60,10 L 20,50 L 60,90 L 50,100 L 0,50 Z" transform="translate(85,100) rotate(180)"
                        fill="currentColor" stroke="currentColor" stroke-width="10"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
<a href="javascript:void(0)" class="closebtn" style="display: none" onclick="closeNav()">
    <svg viewBox="-10 -10 120 120" width="16">
        <path d="M 50,0 L 60,10 L 20,50 L 60,90 L 50,100 L 0,50 Z" fill="currentColor" transform="translate(30,0)"
            stroke="currentColor" stroke-width="10"></path>
    </svg>
</a>
<div id="mySidepanel" class="sidepanel">
    <div class="sideWrapper">
        <table id="clients2" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>ID_empresa</th>
                    <th>Empresa</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Categoría</th>
                    <th>Cod. Postal</th>
                    <th>Ciudad</th>
                    <th>País</th>
                    <th width="100%">Establecimientos</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div id="info-panel" class="popup-fixed"></div>
<!-- Modal de Bootstrap para mensajes -->
<div class="modal fade" id="customBootstrapModal" tabindex="-1" aria-labelledby="customBootstrapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div id="custom-modal-header" class="modal-header bg-warning">
            <h5 id="custom-modal-title" class="modal-title" id="customBootstrapModalLabel">Aviso</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="customBootstrapModalMsg">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
        </div>
        </div>
    </div>
</div>
@push('css')
{{--
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/sl-2.0.3/datatables.min.css"
    rel="stylesheet"> --}}
<link href="{{ asset('build/assets/datatables.min.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
    integrity="sha512-kq3FES+RuuGoBW3a9R2ELYKRywUEQv0wvPTItv3DSGqjpbNtGWVdvT8qwdKkqvPzT93jp8tSF4+oN4IeTEIlQA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    ::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }

    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px grey;
        border-radius: 2px;
    }

    ::-webkit-scrollbar-thumb {
        background: #0A457E;
        border-radius: 2px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #022047;
    }

    body {
        overscroll-behavior-y: none;
    }

    #message {
        color: white;
        background-color: #1c4077;
        text-align: center;
        height: 25px;
    }

    #message>span {
        line-height: 25px;
    }

    #map {
        width: 100%;
        height: calc(100dvh - 125px);
        transition: 0.5s;
    }

    #loading-page {
        left: 0;
        top: 0;
        z-index: 1000;
        width: 100%;
        height: 100%;
        background-color: #dfebef73;
    }

    #loading-page>div {
        position: absolute;
        top: 50%;
        left: 50%;
        height: 5em;
        width: 5em;
    }

    .dt-search {
        display: flex;
        gap: .5rem;
        margin: 0;
        width: 100%;
    }

    .dt-search>input {
        width: 100% !important;
    }

    .select-info {
        display: none;
    }

    .btn.btn-outline-secondary>svg {
        fill: #6c757d;
    }

    .btn.btn-outline-secondary:hover>svg {
        fill: #fff;
    }

    .leaflet-pane.leaflet-fixed-pane {
        z-index: 1400;
        cursor: default;
        top: unset;
        bottom: 0;
        left: 0;
        right: unset;
        width: 100%;
    }

    .popup-fixed {
        position: fixed;
        top: auto;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        transform: unset !important;
        margin: 0;
        max-height: calc(100dvh - 125px);
        overflow-y: auto;
        height: 0;
        background: white;
        transition: .3s all ease;
        border: 1px solid #003B71;
        border-radius: 24px 24px 0 0;
        z-index: 1;
    }

    #clients_wrapper {
        position: relative;
    }

    div.dt-processing {
        top: unset;
        left: unset;
        margin: unset;
        width: 100%;
        height: 100%;
        background-color: #6c757daf;
        top: 0;
        left: 0;
    }

    div.dt-processing>div:last-child {
        top: 30%;
        transform: translateY(-50%);
    }

    /* .dtsp-titleRow{display: none;} */

    .dt-scroll-headInner,
    .table.table-striped.dataTable {
        width: 100% !important;
    }

    .leaflet-popup-tip-container {
        display: none;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 0;
        padding: 6em 3em 1em;
        height: 100%;
        overflow-x: hidden;
    }

    .leaflet-popup-content {
        font-size: 16px;
        margin: 0;
        overflow-y: auto;
    }

    .leaflet-container a.leaflet-popup-close-button {
        width: 35px;
        height: 35px;
        background-color: #0A457E;
        color: white;
        border-radius: 50%;
        top: 5px;
        right: 5px;
        font-size: 30px;
        line-height: 30px;
        box-shadow: 0px 3px 6px #00000029;
    }

    .leaflet-container a.leaflet-popup-close-button:visited,
    .leaflet-container a.leaflet-popup-close-button:focus,
    .leaflet-container a.leaflet-popup-close-button:active,
    .leaflet-container a.leaflet-popup-close-button:hover {
        color: white;
    }

    @keyframes border-pulse {
        0% {
            box-shadow: 0 0 0 0 #0767c2;
        }

        70% {
            box-shadow: 0 0 0 20px rgba(255, 0, 0, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
        }
    }

    .animation {
        border-radius: 50%;
        animation: border-pulse 1.5s infinite;
    }

    div.dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane {
        margin-top: 0 !important;
    }

    .sidepanel {
        width: 0;
        position: fixed;
        z-index: 1000;
        height: calc(100dvh - 125px);
        top: 125px;
        left: 0;
        background-color: #ffffff;
        overflow-x: hidden;
        transition: 0.5s width ease;
        border: 1px solid #003B71;
        border-radius: 0 24px 24px 0;
    }

    .closebtn {
        position: absolute;
        text-decoration: none;
        left: 400px;
        border-radius: 0 50% 50% 0;
        /* border: 1px solid #5e5e5e; */
    }

    .sideWrapper {
        padding: 0 2em;
    }

    .closebtn,
    .openbtn {
        font-size: 20px;
        cursor: pointer;
        background-color: #003B71;
        color: #F7F7F7;
        padding: 10px 15px 10px 5px;
        top: 50%;
        transform: translateY(-50%);
        transition: .3s;
    }

    .openbtn {
        z-index: 1;
        left: 0;
        border-radius: 0 50% 50% 0;
        border: 0;
        background-color: orange;
        color: #1b111b;
    }

    .openbtn:hover,
    .closebtn:hover {
        /* background-color: #5e5e5e; */
        background-color: orange;
        /* border: 1px solid #5e5e5e; */
        color: #f7f7f7;
    }

    .tab-content {
        overflow-y: auto;
        overflow-x: hidden;
        height: 230px;
        max-height: 230px;
    }

    div.dt-container .dt-search {
        position: relative;
        display: inline-block;
    }

    div.dt-container div.dt-search label {
        display: none !important;
    }

    div.dt-container .dt-search input {
        background: #fff;
        padding-left: 30px;
        /* box-shadow: inset 0px 3px 6px #00000029; */
        border-radius: 24px !important;
        height: 45px;
        line-height: 1rem;
        font-size: 1rem;
        margin: 0 !important;
        border: 1px solid #003B71 !important;
        outline-width: 0;
    }

    div.dt-container .dt-search::before {
        content: '\2315';
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%) rotate(270deg);
        font-size: 2rem;
        color: #003B71;
    }

    .dtsp-collapseButton {
        background-color: transparent !important;
        border: 0 !important;
    }

    .dt-scroll {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 3px 6px #00000029;
    }

    .nav-tabs .nav-link.active,
    .nav-tabs .nav-item.show .nav-link {
        border-left: 0;
        border-right: 0;
        border-top: 0;
        border-bottom: 2px solid #0A457E;
        color: #0A457E;
        background: none;
    }

    .nav-tabs .nav-link {
        font-weight: bold;
        color: #5e5e5e;
        border-left: 0;
        border-right: 0;
        border-top: 0;
    }

    .nav-tabs .nav-link:hover,
    .nav-tabs .nav-link:focus {
        border-left: 0;
        border-right: 0;
        border-top: 0;
        border-color: unset;
    }

    .dtsp-topRow.dtsp-subRowsContainer.dtsp-bordered,
    div.dtsp-panesContainer div.dtsp-searchPane div.dtsp-topRow {
        margin: 0;
    }

    div.dtsp-panesContainer div.dtsp-searchPanes {
        gap: .8em;
        text-align: left;
    }

    div.dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane div.dt-container,
    div.dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane div.dataTables_wrapper,
    div.dtsp-panesContainer div.dtsp-searchPane div.dt-container:hover,
    div.dtsp-panesContainer div.dtsp-searchPane div.dataTables_wrapper:hover {
        border: 0 !important;
    }

    .popup-datos {
        text-align: left;
    }

    .popup-datos h2 {
        font-size: 1.8rem;
    }

    .select-info {
        display: none !important;
    }

    .drag-icon {
        display: inherit;
        cursor: grab;
        user-select: none;
        margin-top: 0;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        padding: 1em;

    }

    .drag-icon span {
        height: 4px;
        width: 40px;
        display: block;
        background: #C7D0E1;
        border-radius: 50px;
    }

    .external-link {
        display: inline-block;
        height: auto;
        /* margin-right: 1em; */
        overflow: hidden;
    }

    .external-link a {
        text-align: center;
        display: block
    }

    .external-link a img {
        height: 55px;
        object-fit: contain;
        max-width: 130px;
    }

    @media (max-width: 700px) {
        .closebtn {
            right: 0;
            left: initial;
            background-color: #1c4077;
            border-radius: 0 50% 50% 0;
            border: 1px solid #1c4077;
            z-index: 1001;
        }

        .external-link a img {
            height: 45px;
            max-width: 100px;
        }
    }

    @media (min-width: 700px) {
        .leaflet-pane.leaflet-fixed-pane {
            height: calc(100dvh - 125px);
            border-radius: 10px 0 0 10px;
            overflow: hidden;
            background: white;
            left: unset;
            right: 0;
            width: auto;
        }

        .popup-fixed {
            width: 0;
            height: 100dvh !important;
            right: 0 !important;
            left: unset !important;
            position: fixed;
            /* min-height: 800px; */
            max-height: calc(100dvh - 125px);
            top: 125px;
            box-shadow: -2px 0px 10px gray;
            border-radius: 24px 0 0 24px;
        }

        .leaflet-popup-content {
            height: 100%;
            width: 100% !important;
        }

        .drag-icon {
            display: none
        }
    }

    .dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane,
    .dtsp-topRow.dtsp-subRowsContainer.dtsp-bordered {
        position: relative;
    }

    .dtsp-subRow2 {
        position: absolute;
        right: 0;
    }

    .dtsp-searchPane div.dt-container {
        position: absolute;
        z-index: 1;
    }

    .dtsp-searchIcon {
        display: none;
    }

    #ubc-filter {
        gap: .3rem;
    }

    div.dtsp-panesContainer div.dtsp-searchPane div.dtsp-topRow button {
        line-height: 35px;
    }

    div.dtsp-panesContainer div.dtsp-searchPane div.dtsp-topRow button.dtsp-rotated span {
        top: unset;
    }

    .dt-scroll-body {
        height: 230px;
    }

    .dtsp-searchPane .dt-scroll-body {
        height: 150px !important;
    }


    .multiselect-container {
        position: relative;
        /* width: 180px; */
    }

    .multiselect {
        width: auto;
        padding: 8px;
        border: 1px solid #003B71;
        border-radius: 8px;
        background-color: #fff;
        cursor: pointer;
        color: #003B71;
    }

    .multiselect-options {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        width: 150px;
        . border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #fff;
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .multiselect-options label {
        display: block;
        padding: 8px 10px;
        cursor: pointer;
    }

    .multiselect-options label:hover {
        background-color: #f0f0f0;
    }

    .multiselect-options input[type="checkbox"] {
        margin-right: 10px;
    }

    .multiselect.active .multiselect-options {
        display: block;
    }

    #familias {
        background: transparent;
        left: 0;
        position: absolute;
        top: calc(25px + .7rem);
        z-index: 1;
        gap: .5rem;
        width: 100dvw;
        justify-content: center;
        padding: 1rem 4em;
        overflow-x: auto;
        transition: .3s;
        scroll-behavior: smooth;
    }

    #familias>span {
        background-color: white;
        color: black;
        font-weight: 500;
        border-radius: 0.3rem;
        padding: .5rem .4rem;
        cursor: pointer;
        width: fit-content;
        min-width: 100px;
        text-align: center;
        box-shadow: 1px 2px 10px gray;
        margin-bottom: .2rem;
        transition: .3s all ease-in;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        flex-shrink: 0;
    }

    #familias>span.selected {
        font-weight: bold;
        color: white;
        background-color: #1c4175;
    }

    #fam_mob {
        position: absolute;
        right: 5px;
        top: 35px;
        z-index: 1;
    }

    .fam-pills {
        background: #fff;
        color: #003B71;
        border: 1px solid #003B71;
        border-radius: 1.5rem;
        padding: 2px 10px;
        cursor: pointer;
    }

    .fam-pills.selected {
        background: #003B71;
        color: #fff;
    }

    .fam-pills-info {
        display: none;
    }

    .fam-pills-info.selected {
        display: block;
    }

    tr.selected {
        background-color: yellow;
    }

    .closePopUpButton {
        position: absolute;
        right: 1.5em;
        width: 35px;
        height: 35px;
        background-color: #1c4175;
        color: white;
        border-radius: 50%;
        top: 1.5em;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-weight: bold;
    }

    .leaflet-popup-content p {
        margin: 2em 0 1em;
    }

    .separator-prods {
        box-shadow: 0px 3px 6px #00000029;
        border: 1px solid #FFFFFF;
    }

    td.min-mobile-p.dtr-control {
        font-weight: 600;
    }

    table.dataTable thead>tr>th.dt-orderable-asc span.dt-column-order:before,
    table.dataTable thead>tr>th.dt-orderable-asc span.dt-column-order:after,
    table.dataTable thead>tr>th.dt-orderable-desc span.dt-column-order:before,
    table.dataTable thead>tr>th.dt-orderable-desc span.dt-column-order:after,
    table.dataTable thead>tr>th.dt-ordering-asc span.dt-column-order:before,
    table.dataTable thead>tr>th.dt-ordering-asc span.dt-column-order:after,
    table.dataTable thead>tr>th.dt-ordering-desc span.dt-column-order:before,
    table.dataTable thead>tr>th.dt-ordering-desc span.dt-column-order:after,
    table.dataTable thead>tr>td.dt-orderable-asc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-orderable-asc span.dt-column-order:after,
    table.dataTable thead>tr>td.dt-orderable-desc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-orderable-desc span.dt-column-order:after,
    table.dataTable thead>tr>td.dt-ordering-asc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-ordering-asc span.dt-column-order:after,
    table.dataTable thead>tr>td.dt-ordering-desc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-ordering-desc span.dt-column-order:after {
        opacity: 0;
        font-size: 1.2em;
    }



    table.dataTable thead>tr>th.dt-ordering-asc span.dt-column-order:before,
    table.dataTable thead>tr>th.dt-ordering-desc span.dt-column-order:after,
    table.dataTable thead>tr>td.dt-ordering-asc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-ordering-desc span.dt-column-order:after {
        opacity: .6;
        width: 45px;
        left: -25px;
    }

    table.dataTable thead>tr>th.dt-orderable-asc span.dt-column-order:before,
    table.dataTable thead>tr>th.dt-ordering-asc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-orderable-asc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-ordering-asc span.dt-column-order:before {
        bottom: 40%;
    }

    table.dataTable thead>tr>th.dt-orderable-desc span.dt-column-order:after,
    table.dataTable thead>tr>th.dt-ordering-desc span.dt-column-order:after,
    table.dataTable thead>tr>td.dt-orderable-desc span.dt-column-order:after,
    table.dataTable thead>tr>td.dt-ordering-desc span.dt-column-order:after {
        top: 40%;
    }

    table.dataTable thead>tr>th.dt-orderable-desc span.dt-column-order:after,
    table.dataTable thead>tr>th.dt-ordering-desc span.dt-column-order:after {
        content: "AZ \1F851" /"";
    }

    table.dataTable thead>tr>th.dt-orderable-asc span.dt-column-order:before,
    table.dataTable thead>tr>th.dt-ordering-asc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-orderable-asc span.dt-column-order:before,
    table.dataTable thead>tr>td.dt-ordering-asc span.dt-column-order:before {
        content: "AZ \1F853" /"";
    }

    .select2-container--default .select2-results>.select2-results__options,
    .select2-dropdown {
        border-radius: 8px !important;
    }

    .select2-dropdown {
        overflow: hidden;
    }

    @media (max-width: 700px) {
        #familias {
            left: 3.3rem;
            width: calc(100dvw - 3.3rem);
            padding: 0;
            overflow-x: auto;
            justify-content: start;
        }

        #familias>span {
            padding: .3rem;
        }
    }
</style>
@endpush
@push('js')
{{-- <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/r-3.0.2/sp-2.3.1/sl-2.0.3/datatables.min.js">
</script> --}}
<script src="{{ asset('build/assets/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    (g => {var h, a, k, p = "The Google Maps JavaScript API",c = "google",l = "importLibrary",q = "__ib__",m = document,b = window;b = b[c] || (b[c] = {});var d = b.maps || (b.maps = {}),r = new Set,e = new URLSearchParams,u = () => h || (h = new Promise(async (f, n) => {await (a = m.createElement("script"));e.set("libraries", [...r] + "");for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);e.set("callback", c + ".maps." + q);a.src = `https://maps.${c}apis.com/maps/api/js?` + e;d[q] = f;a.onerror = () => h = n(Error(p + " could not load."));a.nonce = m.querySelector("script[nonce]")?.nonce || "";m.head.append(a)}));d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>d[l](f, ...n))})({key: "AIzaSyBhZrCRmu3o1CQ4w7dIo6SC_41BYgaeFfM",v: "weekly",libraries: ["geometry"]});
</script>
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script>
    let empresas = @json($companies);
    let categorias = @json($categories);
    let paises = @json($paises);
    let ciudades = @json($ciudades);
    let zipcodes = @json($zipcodes);
    let familias = @json($familias);
    var $eventSelect = null;
    let familasSel = "";
    let zipcodesSel ="";
    let ciudadesSel ="";
    let paisesSel ="";
    let categoriasSel ="";
    let empresasSel ="";
    let cluster;
    let markers;
    let allMarkers = [];
    let paintedMarkers = [];
    let currentMarkerAnimated;
    const config = {
        minZoom: 5,
        maxZoom: 18
    };
    const zoom = 15;
    let map;
    let noGPS = false;
    let table;
    let pane;
    let xhr = null;
    let movil = false;
    let navOpened = false;
    let openInfo = false;
    // Global variables for tracking drag events
    let isDragging = false,
        startY, startHeight;
    let sheetContent = null;
    let dragIcon = null;
    let bottomSheet;
    const urlParams = new URLSearchParams(window.location.search);
    const loc = urlParams.get('loc');
    let clients;
    let clientIndex = 0;
    let clientInterval = null;
    let intervalTime = 0;
    let compLatitude = 0.0;
    let compLongitude = 0.0;
    let compName = '';

    $(document).ready(function() {
        table = new DataTable('#clients2', configDes);
        $('.select-multiple').select2({
            tags: true,
            tokenSeparators: [',', ' '],
        });
        paintTable({location:loc});
        table.off('search.dt').on('search.dt', function() {
            $('#loading-page').show();
            let companiesF = [];
            let categoriesF = [];
            let paisesF = [];
            let ciudadesF = [];
            let zipcodesF = [];
            let familiasF = [];
            paintedMarkers = allMarkers;

            if (empresasSel.length > 0)
                companiesF = empresasSel.split("|");
            else
                companiesF = [];

            if (familasSel.length > 0)
                familiasF = familasSel.split("|");
            else
                familiasF = [];

            if (paisesSel.length > 0)
                paisesF = paisesSel.split("|");
            else
                paisesF = [];

            if (ciudadesSel.length > 0)
                ciudadesF = ciudadesSel.split("|");
            else
                ciudadesF = [];

            if (zipcodesSel.length > 0)
                zipcodesF = zipcodesSel.split("|");
            else
                zipcodesF = [];

            if (categoriasSel.length > 0)
                categoriesF = categoriasSel.split("|");
            else
                categoriesF = [];

            if (cluster !== undefined) {
                // Clear markers for filtering
                cluster.clearMarkers([], true);

                let filters = {
                    empresas: companiesF,
                    familias: familiasF,
                    paises: paisesF,
                    ciudades: ciudadesF,
                    zipcodes: zipcodesF,
                    categorias: categoriesF
                };
                paintedMarkers = applyFilters(paintedMarkers, filters);
                if (companiesF.length == 0 && familiasF.length == 0 && categoriesF.length == 0 &&
                    paisesF.length == 0 && ciudadesF.length == 0 && zipcodesF.length == 0) {
                    cluster.addMarkers(allMarkers);
                } else {
                    cluster.addMarkers(paintedMarkers);
                }
            }
            $('#loading-page').hide();
        });

        $('.select-multiple').on('change', function(e) {
            // console.log('Empresas:', $('#sEmpresas').val(), "Pais:", $('#sPaises').val(), "Ciudad:", $('#sCiudades').val(),
            //     "Zipcode:", $('#sZipcodes').val(), "Categorias:", $('#sCategorias').val(), "Familias:", $('#sFamilias').val());
            if($('#sEmpresas').val().length > 0){
                empresasSel = $('#sEmpresas').val().join('|');
                table.columns(1).search(empresasSel, true, false, false, false).draw();
            }
            if($('#sPaises').val().length > 0){
                paisesSel = $('#sPaises').val().join('|');
                table.columns(7).search(paisesSel, true, false, false, false).draw();
            }
            if($('#sCiudades').val().length > 0){
                ciudadesSel = $('#sCiudades').val().join('|');
                var searchTermsArray = $('#sCiudades').val().map(term => '^' + term.trim() + '$');
                var regexSearchTerm = searchTermsArray.join('|');
                table.columns(6).search(regexSearchTerm, true, false).draw();
            }
            if($('#sZipcodes').val().length > 0){
                zipcodesSel = $('#sZipcodes').val().join('|');
                table.columns(5).search(zipcodesSel, true, false, false, false).draw();
            }
            if($('#sCategorias').val().length > 0){
                categoriasSel = $('#sCategorias').val().join('|');
                table.columns(4).search(categoriasSel, true, false, false, false).draw();
            }
            if($('#sFamilias').val().length > 0){
                familasSel = $('#sFamilias').val().join('|');
                table.columns(9).search(familasSel, true, false, false, false).draw();
            }
            if($('#sEmpresas').val().length == 0 && $('#sPaises').val().length == 0 && $('#sCiudades').val().length == 0 &&
                $('#sZipcodes').val().length == 0 && $('#sCategorias').val().length == 0 && $('#sFamilias').val().length == 0){
                    empresasSel = [];
                    paisesSel = [];
                    ciudadesSel = [];
                    zipcodesSel = [];
                    categoriasSel = [];
                    familasSel = [];
                    table.columns(1).search('').draw();
            }

            $('.added-svg').remove();
            $('.familia.selected').removeClass('selected');
            $('#sFamilias').val().forEach(element => {
                let ele = element.split(" ").join("");
                $('#f_' + ele).addClass('selected');
                $('#f_' + ele).append(`<svg class="added-svg" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="check">
                                        <path fill="none" d="M0 0h24v24H0V0z"></path>
                                        <path d="M9 16.17L5.53 12.7c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l4.18 4.18c.39.39 1.02.39 1.41 0L20.29 7.71c.39-.39.39-1.02 0-1.41-.39-.39-1.02-.39-1.41 0L9 16.17z" fill="currentColor" stroke="currentColor" stroke-width="2" svgShape"></path>
                                </svg>`);

                let checkbox = document.querySelector(`.multiselect-options input[type="checkbox"][value="${element}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }

            });
        });

        $(document).ajaxComplete(function(event, xhr, settings) {
            settleStructure();
            // startClientInterval();
        });

        $('.familia').click(function() {
            if ($(this).hasClass('selected')) {
                // Remove the 'selected' class and SVG if it exists
                $(this).removeClass('selected');
                $(this).find('.added-svg').remove();
            } else {
                // Add the 'selected' class and append the SVG
                $(this).addClass('selected');
                $(this).append(`<svg class="added-svg" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="check">
                                        <path fill="none" d="M0 0h24v24H0V0z"></path>
                                        <path d="M9 16.17L5.53 12.7c-.39-.39-1.02-.39-1.41 0-.39.39-1.02.39-1.41 0L20.29 7.71c.39-.39.39-1.02 0-1.41-.39-.39-1.02-.39-1.41 0L9 16.17z" fill="currentColor" stroke="currentColor" stroke-width="2" svgShape"></path>
                                </svg>`);
            }
            sel = $('.familia.selected').map(function() {
                return this.innerText;
            }).get();
            sel.forEach(value => {
                const checkbox = document.querySelector(`.multiselect-options input[type="checkbox"][value="${value}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
            $('#sFamilias').val(sel);
            $('#sFamilias').trigger('change');
            if (sel.length == 0) {
                table.draw();
            }
        });
        bottomSheet = document.querySelector("#info-panel");
    });

    function settleStructure(){
        $("#emp-filter").prepend($(".sp-original-emp"));
        $("#ubc-filter").prepend($(".sp-original-ubc"));
        $('#clients2_wrapper>div').addClass('mt-4');
        $('#clients2_wrapper>div').eq(-2).hide();
        $('#clients2_wrapper>div').last().removeClass('mt-4');
        $('.dtsp-caret').text('');
        $('.dtsp-caret').html(
            '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"><path fill="#1c4077" d="M23.677 18.52c.914 1.523-.183 3.472-1.967 3.472h-19.414c-1.784 0-2.881-1.949-1.967-3.472l9.709-16.18c.891-1.483 3.041-1.48 3.93 0l9.709 16.18z"/></svg>'
        );
        $(".dt-search>input").attr('placeholder', 'Buscar establecimiento')
        $('.dtsp-titleRow').first().parent().parent().parent().hide();
        if ($('#emp-filter').children().length > 0) {
            sheetContent = document.querySelector(".popup-fixed");
            dragIcon = document.querySelector(".drag-icon");
            if (dragIcon !== null) {
                dragIcon.addEventListener("touchstart", dragStart);
                document.addEventListener("touchmove", dragging);
                document.addEventListener("touchend", dragStop);
            }
        }
        $('#loading-page').hide();
    }

    async function initMap() {
        console.log('init map');
        const {Map} = await google.maps.importLibrary("maps");

        map = new Map(document.getElementById("map"), {
            center: {
                lat: 41.3954446,
                lng: 2.1510205
            },
            zoom: 8,
            minZoom: 3,
            mapTypeId: 'roadmap',
            mapId: "4504f8b37365c3d0",
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
        });
    }

    function applyFilters(markers, filters) {
        return markers.filter(marker => {
            // Filtrar por zipcode
            const zipcodeMatch = filters.zipcodes.length === 0 || filters.zipcodes.some(zipcode => marker.info.zipcode === zipcode);

            // Filtrar por ciudad
            const ciudadMatch = filters.ciudades.length === 0 || filters.ciudades.some(ciudad => marker.info.ciudad === ciudad);

            // Filtrar por país
            const paisMatch = filters.paises.length === 0 || filters.paises.some(pais => marker.info.id_pais === pais);

            // Filtrar por categoría
            const categoriaMatch = filters.categorias.length === 0 || filters.categorias.some(categoria => marker.info.tipocliente === categoria);

            // Filtrar por familia
            const familiaMatch = filters.familias.length === 0 || (marker.info.familia && filters.familias.some(familia => marker.info.familia.split(',').some(f => f.trim() === familia)));

            // Filtrar por empresa
            const empresaMatch = filters.empresas.length === 0 || filters.empresas.some(empresa => marker.info.Empresa === empresa);

            // Retornar true si todos los filtros coinciden
            return zipcodeMatch && ciudadMatch && paisMatch && categoriaMatch && familiaMatch && empresaMatch;
        });
    }


    async function chargeMap(elements) {
        //add mark to company location
        const {AdvancedMarkerElement} = await google.maps.importLibrary("marker");
        const parser = new DOMParser();

        const locationSvgString =
        `<svg width="40" height="40" class="marker" viewBox="-7 -5 63 63" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g>
                <circle style="fill:#ffa53261; stroke:#FFA100; stroke-width:2;" cx="25" cy="26.5" r="31"/>
                <path style="fill:#FFA100;" d="M8 2L8 6L4 6L4 48L15 48L15 39L19 39L19 48L30 48L30 6L26 6L26 2 Z M 10 10L12 10L12 12L10 12 Z M 14 10L16 10L16 12L14 12 Z M 18 10L20 10L20 12L18 12 Z M 22 10L24 10L24 12L22 12 Z M 32 14L32 18L34 18L34 20L32 20L32 22L34 22L34 24L32 24L32 26L34 26L34 28L32 28L32 30L34 30L34 32L32 32L32 34L34 34L34 36L32 36L32 38L34 38L34 40L32 40L32 42L34 42L34 44L32 44L32 48L46 48L46 14 Z M 10 15L12 15L12 19L10 19 Z M 14 15L16 15L16 19L14 19 Z M 18 15L20 15L20 19L18 19 Z M 22 15L24 15L24 19L22 19 Z M 36 18L38 18L38 20L36 20 Z M 40 18L42 18L42 20L40 20 Z M 10 21L12 21L12 25L10 25 Z M 14 21L16 21L16 25L14 25 Z M 18 21L20 21L20 25L18 25 Z M 22 21L24 21L24 25L22 25 Z M 36 22L38 22L38 24L36 24 Z M 40 22L42 22L42 24L40 24 Z M 36 26L38 26L38 28L36 28 Z M 40 26L42 26L42 28L40 28 Z M 10 27L12 27L12 31L10 31 Z M 14 27L16 27L16 31L14 31 Z M 18 27L20 27L20 31L18 31 Z M 22 27L24 27L24 31L22 31 Z M 36 30L38 30L38 32L36 32 Z M 40 30L42 30L42 32L40 32 Z M 10 33L12 33L12 37L10 37 Z M 14 33L16 33L16 37L14 37 Z M 18 33L20 33L20 37L18 37 Z M 22 33L24 33L24 37L22 37 Z M 36 34L38 34L38 36L36 36 Z M 40 34L42 34L42 36L40 36 Z M 36 38L38 38L38 40L36 40 Z M 40 38L42 38L42 40L40 40 Z M 10 39L12 39L12 44L10 44 Z M 22 39L24 39L24 44L22 44 Z M 36 42L38 42L38 44L36 44 Z M 40 42L42 42L42 44L40 44Z"/>
            </g>
        </svg>`;
        const locationSvg = parser.parseFromString(locationSvgString, "image/svg+xml", ).documentElement;
        const marker = new google.maps.marker.AdvancedMarkerElement({
            position: {
                lat: compLatitude,
                lng: compLongitude,
            },
            content: locationSvg,
            title: compName,
            map,
        });

        console.log('charge map');
        if (elements !== null) {
            const pinSvgString =
                `<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="40" height="40" viewBox="0 0 75.6 100" class="marker">
                    <g class="mapMarker">
                        <path fill="#123f75" stroke-width="0" d="M67,14.2C61,6.5,52.2,1.6,42.6.4c-1.6-.2-3.2-.3-4.8-.4h0c-1.6,0-3.2.1-4.8.4C23.4,1.6,14.6,6.5,8.6,14.2,2.1,22-.9,32,.2,42.1c.5,6.4,2.8,12.6,6.5,17.9,9.7,13.2,19.6,26.3,29.4,39.5.2.2.3.5.5.5h2.4c.2,0,.3-.3.5-.5,9.8-13.2,19.7-26.3,29.4-39.5,3.7-5.3,6-11.5,6.5-17.9,1.1-10.1-1.9-20.1-8.4-27.9Z"/>
                        <path fill="#fff" stroke-width="0" d="M47.6,20.6c-1.1,0-2.1.4-2.9,1.1l-15.5,15.5v-10.1c0-7-9.3-6.4-9.3-6.4v32.2l9.3-9.3,22.9-23h-4.5Z"/>
                        <path fill="#fff" stroke-width="0" d="M44.7,43.5h-6.6s13.1-13.1,14.2-14.2c.7-.8,1.1-1.8,1.1-2.9v-4.6s-20.6,20.6-22.2,22.2c-3,3-1.3,8.8,3.6,8.8h19.9c-1.2-3.6-4.5-9.3-10-9.3"/>
                        <path fill="#fff" stroke-width="0" d="M54.5,41.7h-8.4c6.1,1.4,9.1,5.4,10.6,11.4v-9.1c0-1.2-.8-2.2-2-2.3h-.3"/>
                    </g>
                </svg>`;
            markers = elements.clients.map((element, i) => {
                const pinSvg = parser.parseFromString(pinSvgString, "image/svg+xml", ).documentElement;
                const AdvancedMarkerElement = new google.maps.marker.AdvancedMarkerElement({
                    position: {
                        lat: +element.latitud,
                        lng: +element.longitud,
                    },
                    content: pinSvg,
                    title: element.nombre,
                    map,
                });
                AdvancedMarkerElement.info = element;
                AdvancedMarkerElement.addListener("click", () => {
                    // Remover animación del marcador anterior
                    if (currentMarkerAnimated !== undefined) {
                        currentMarkerAnimated.content.classList.remove("animation");
                    }

                    // Animar la transición con zoom
                    animateMarkerTransition(AdvancedMarkerElement, () => {
                        // Después de la animación, aplicar la clase y cargar el popup
                        currentMarkerAnimated = AdvancedMarkerElement;
                        AdvancedMarkerElement.content.classList.add("animation");

                        requestAnimationFrame(() => {
                            loadPopUp(element.id_cliente, element.id_empresa, element.Empresa,
                                    element.direccion, element.nombre, element.familia);
                        });
                    });
                });
                return AdvancedMarkerElement;
            });
            allMarkers = markers;
            paintedMarkers = allMarkers;
            const options = new markerClusterer.GridAlgorithm({
                gridSize: 60
            });
            cluster = new markerClusterer.MarkerClusterer({
                options,
                map,
                markers
            });
        } else {
            showBootstrapModal(
                'Por favor revisa la configuración de centro de trabajo de la aplicación.',
                'La URL es incorrecta',
                'danger',
                "{{ route('indexLocation') }}");
        }
        return Promise.resolve();
    }

    function tabAction(e) {
        e.preventDefault()
        let a = e.target.id;
        a = a.substring(0, a.length - 4);
        $('#pills-tab a[href="#' + a + '"]').tab('show')
    }

    const configDes = {
        // ordering: false,
        // data: JSON.parse( sessionStorage.getItem('data') ),
        searching: true,
        search: {
            caseInsensitive: true,
            smart: false
        },
        responsive: true,
        processing: true,
        // searchPane: false,
        // pagingType: 'full_numbers',
        columns: [{
                data: "id_empresa",
                className: "min-mobile-p",
                visible: false
            },
            {
                data: 'Empresa',
                visible: false,
                // searchPanes: {
                //     header: 'Empresa',
                //     show: true,
                //     // initCollapsed: true,
                //     className: 'sp-original-emp',
                //     orderable: false,
                //     collapse: false,
                //     clear: false,
                // },
            },
            {
                data: "nombre",
                className: "min-mobile-p",
                orderable: false,
                visible: false
            },
            {
                data: "direccion",
                className: "min-mobile-p",
                orderable: false,
                visible: false
            },
            {
                data: "tipocliente",
                className: "min-mobile-p",
                visible: false,
                // searchPanes: {
                //     show: true,
                //     combiner: 'or',
                //     initCollapsed: true,
                //     className: 'sp-original-ubc',
                //     orderable: false,
                //     clear: false,
                // },
            },
            {
                data: "zipcode",
                visible: false,
                className: "min-mobile-p",
                // searchPanes: {
                //     header: 'Cód. Postal',
                //     show: true,
                //     combiner: 'or',
                //     initCollapsed: true,
                //     className: 'sp-original-ubc',
                //     orderable: false,
                //     clear: false,
                // }
            },
            {
                data: "ciudad",
                visible: false,
                className: "min-mobile-p",
                // searchPanes: {
                //     header: 'Ciudad',
                //     show: true,
                //     combiner: 'or',
                //     initCollapsed: true,
                //     className: 'sp-original-ubc',
                //     orderable: false,
                //     clear: false,
                // }
            },
            {
                data: "id_pais",
                visible: false,
                className: "min-mobile-p",
                // searchPanes: {
                //     header: 'CCAA',
                //     show: true,
                //     combiner: 'or',
                //     initCollapsed: true,
                //     className: 'sp-original-ubc',
                //     orderable: false,
                //     clear: false,
                // }
            },
            {
                data: "accion",
                className: "min-mobile-p",
                orderable: true
            },
            {
                data: "familia",
                className: "min-mobile-p",
                orderable: false,
                visible: false,
            },
        ],
        layout: {
            // top1: {
            //     // searchPanes: {
            //     //     className: 'def-filters',
            //     //     dtOpts: {
            //     //         select: {
            //     //             style: 'multi'
            //     //         }
            //     //     },
            //     //     viewTotal: true,
            //     //     columns: [7, 6, 5, 1, 4],
            //     //     clear: false,
            //     // }
            // },
            top2: 'search',
            top1: function() {
                let toolbar = document.createElement('div');
                let sEmpresas = "";
                let sPaises = "";
                let sCiudades = "";
                let sZipcodes = "";
                let sCategorias = "";
                let sFamilias = "";

                if(empresas != null){

                    empresas.forEach(e => {
                        sEmpresas +=`<option value="${e.name}">${e.name}</option>`;
                    });
                }
                if(categorias != null){
                    categorias.forEach(c => {
                        sCategorias +=`<option value="${c}">${c}</option>`;
                    });
                }
                if(familias != null){
                    familias.forEach(f => {
                        sFamilias +=`<option value="${f}">${f} </option>`;
                    });
                }
                if(paises != null){
                    paises.forEach(p => {
                        sPaises +=`<option value="${p}">${p}</option>`;
                    });
                }
                if(ciudades != null){
                    ciudades.forEach(c => {
                        sCiudades +=`<option value="${c}">${c}</option>`;
                    });
                }
                if(zipcodes != null){
                    zipcodes.forEach(z => {
                        sZipcodes +=`<option value="${z}">${z}</option>`;
                    });
                }

                toolbar.innerHTML =
                    `<ul class="nav nav-tabs  nav-justified mb-3 flex-nowrap" id="pills-tab" role="tablist">
                        <li class="nav-item"><a onclick="tabAction(event)" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Empresas</a></li>
                        <li class="nav-item"><a onclick="tabAction(event)" class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Ubicación</a></li>
                        <li class="nav-item"><a onclick="tabAction(event)" class="nav-link" id="pills-cat-tab" data-toggle="pill" href="#pills-cat" role="tab" aria-controls="pills-cat" aria-selected="false">Categorías</a></li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="dtsp-panes dtsp-panesContainer">
                                <div id="emp-filter" class="dtsp-searchPanes" style="position:relative">
                                    <select id="sEmpresas" class="select-multiple" name="emps[]" multiple="multiple" data-placeholder="Filtre las empresas">
                                        ${sEmpresas}
                                    </select>
                                    <div style="margin-top:1em" class="label-select-multiple">Tiendas Online</div>
                                    <div class="d-flex flex-wrap gap-2" style="width:100%; padding:1em 0.1em;">
                                        <div class="external-link"><a href="https://ibericbox.com/es/" target="_blank"><img src="{{ asset('images/IbericBox.png') }}"/></a></div>
                                        <div class="external-link"><a href="https://www.ladespensarodriguez.es/" target="_blank"><img src="{{ asset('images/embRodriguez.png') }}"/></a></div>
                                        <div class="external-link"><a href="https://rubiatoparedes.com/tienda/" target="_blank"><img src="{{ asset('images/RP.png') }}"/></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="dtsp-panes dtsp-panesContainer">
                                <div id="ubc-filter" class="dtsp-searchPanes">
                                    <label for="sPaises" class="label-select-multiple">
                                    CCNA
                                        <select id="sPaises" class="select-multiple" name="pais[]" multiple="multiple" data-placeholder="Filtra por CCNA">
                                            ${sPaises}
                                        </select>
                                    </label>
                                    <label for="sCiudades" class="label-select-multiple mt-2">
                                    Ciudad
                                    <select id="sCiudades" class="select-multiple" name="cius[]" multiple="multiple" data-placeholder="Filtra por ciudad">
                                        ${sCiudades}
                                    </select>
                                    </label>
                                    <label for="sZipcodes" class="label-select-multiple mt-2">
                                    Cód. Postal
                                    <select id="sZipcodes" class="select-multiple" name="zcds[]" multiple="multiple" data-placeholder="Filtra por código Postal">
                                        ${sZipcodes}
                                    </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-cat" role="tabpanel" aria-labelledby="pills-cat-tab">
                            <div class="dtsp-panes dtsp-panesContainer">
                                <div id="ubc-filter" class="dtsp-searchPanes">
                                    <label for="sCategorias" class="label-select-multiple">
                                    Tipo de establecimiento
                                    <select id="sCategorias" class="select-multiple" name="cats[]" multiple="multiple" data-placeholder="Filtra por tipo de establecimiento">
                                        ${sCategorias}
                                    </select>
                                    </label>
                                    <label for="sFamilias" class="label-select-multiple mt-2">
                                    Tipo de producto
                                    <select id="sFamilias" class="select-multiple" name="fams[]" multiple="multiple" data-placeholder="Filtra por tipo de producto">
                                        ${sFamilias}
                                    </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>`;
                return toolbar;
            },
            topStart: null,
            topEnd: null,
            bottomStart: 'pageLength',
            bottomEnd: null,
            bottom2Start: 'info',
            bottom2End: 'paging'
        },
        // select: {
        //     style: 'os',
        //     selector: 'td:first-child'
        // },
        order: [
            [2, 'asc']
        ],
        language: {
            info: 'Mostrando: _START_ / _END_ - _TOTAL_ registros',
            infoFiltered: "(de un total de _MAX_ registros)",
        },
        deferRender:    true,
        scroller:       true,
        scrollY: 270,
        order:[[8,'asc']],
        columnDefs: [
            { orderSequence: ['asc', 'desc'], targets: [8] },
        ]
    };

    function paintTable(data = "") {
        try {
            if (typeof(Storage) !== 'undefined') {
                let ms = sessionStorage.getItem('created_at');
                let data_tmp_sergi = null;
                let h = ((Date.now() - ms) / (1000 * 60 * 60)).toFixed(1);
                //if(h > 2){
                if(1==1){
                    sessionStorage.removeItem("data");
                }
                if (sessionStorage.getItem("data") == null) {
                    $.ajax({
                        url: "{{ route('getClientLocation') }}",
                        type: 'GET',
                        dataType: "json",
                        data: data,
                        beforeSend: function() {
                            $('.dt-empty').attr('colspan', '9');
                            initMap();
                            // $('#clients2_processing').show();
                            markers = [];
                            // table.clear().draw();
                        },
                        success: function(response) {
                            //sessionStorage.setItem('data', JSON.stringify(response));
                            sessionStorage.setItem('created_at', Date.now());
                            data_tmp_sergi = response;
                            ciudades = response.ciudades;
                            zipcodes = response.zipcodes;
                            categorias = response.categories;
                            familias = response.familias;
                            intervalTime = response.time;
                            compLatitude =  parseFloat(response.compLatitude);
                            compLongitude = parseFloat(response.compLongitude);
                            compName = response.compName;
                            //set the filters data
                            empresas = $.map(response.companies, function (obj) {
                                obj.id = obj.name;
                                obj.text = obj.name;
                                return obj;
                            });
                            paises = $.map(response.paises, function (obj) {
                                let oObj = new Object();
                                oObj.id = obj;
                                oObj.text = obj;
                                return oObj;
                            });
                            ciudades = $.map(response.ciudades, function (obj) {
                                let oObj = new Object();
                                oObj.id = obj;
                                oObj.text = obj;
                                return oObj;
                            });
                            zipcodes = $.map(response.zipcodes, function (obj) {
                                let oObj = new Object();
                                oObj.id = obj;
                                oObj.text = obj;
                                return oObj;
                            });
                            categorias = $.map(response.categories, function (obj) {
                                let oObj = new Object();
                                oObj.id = obj;
                                oObj.text = obj;
                                return oObj;
                            });
                            familias = $.map(response.familias, function (obj) {
                                let oObj = new Object();
                                oObj.id = obj;
                                oObj.text = obj;
                                return oObj;
                            });
                            $("#sEmpresas").select2({
                                data: empresas
                            });
                            $("#sPaises").select2({
                                data: paises
                            });

                            $("#sCiudades").select2({
                                data: ciudades
                            });
                            $("#sZipcodes").select2({
                                data: zipcodes
                            });
                            $("#sCategorias").select2({
                                data: categorias
                            });
                            $("#sFamilias").select2({
                                data: familias
                            });
                            //set the table data
                            clients = response.clients;
                            table.rows.add(response.clients).draw();
                        },
                        statusCode: {
                            403: function() {
                                showBootstrapModal("Intenta iniciar sesión nuevamente")
                            }
                        },
                        complete: function(xhr, status) {
                            chargeMap(data_tmp_sergi).then(() => {
                                startClientInterval();
                            });
                            if (!table.rows().count()) {
                                table.rows.add(data_tmp_sergi.clients).draw();
                            }
                            // openNav();
                        },
                    });
                } else {
                    new Promise(async(resolve, reject)=> {
                        await initMap();
                        await chargeMap(JSON.parse(sessionStorage.getItem('data')));
                    });
                    // initMap();
                    // chargeMap(JSON.parse(sessionStorage.getItem('data')));
                    if (!table.rows().count()) {
                        table.rows.add(JSON.parse(sessionStorage.getItem('data'))).draw();
                        // table.searchPanes.rebuildPane();
                        // openNav();
                    }
                    settleStructure();
                }
            }
        } catch (error) {
            console.log(error);
            showBootstrapModal('Ha habido un error al momento de cargar los datos de la tabla')
        }
    }

    function startClientInterval() {
        if (clientInterval) clearInterval(clientInterval);
        if (!clients || clients.length === 0) return;

        clientIndex = 0;
        ubicarClienteDatos(clients[clientIndex].id_cliente)

        clientInterval = setInterval(() => {
            clientIndex = (clientIndex + 1) % clients.length;
            ubicarClienteDatos(clients[clientIndex].id_cliente)
        }, intervalTime * 1000);
    }

    function ubicarClienteDatos(id_cliente){
        mark = paintedMarkers.find((m => m.info.id_cliente == id_cliente));

        if (currentMarkerAnimated !== undefined) {
            currentMarkerAnimated.content.classList.remove("animation");
        }

        currentMarkerAnimated = mark;

        // Usar la versión optimizada
        animateMarkerTransition(mark, () => {
            mark.content.classList.add("animation");
        });
    }

    function ubicarCliente(el) {
        let id_cliente = $(el).data('id');
        mark = paintedMarkers.find((m => m.info.id_cliente == id_cliente));

        if (currentMarkerAnimated !== undefined) {
            currentMarkerAnimated.content.classList.remove("animation");
        }

        currentMarkerAnimated = mark;

        // Usar la versión optimizada
        animateMarkerTransition(mark, () => {
            mark.content.classList.add("animation");
        });
    }

    function updateSheetHeight(height) {
        sheetContent.style.height = `${height}dvh`;
    }

    // Sets initial drag position, sheetContent height and add dragging class to the bottom sheet
    const dragStart = (e) => {
        isDragging = true;
        startY = e.touches?.[0].pageY;
        startHeight = parseInt(sheetContent.style.height);

        bottomSheet.classList.add("dragging");
    }

    // Calculates the new height for the sheet content and call the updateSheetHeight function
    const dragging = (e) => {
        if (!isDragging) return;
        const delta = startY - (e.touches?.[0].pageY);
        const newHeight = startHeight + delta / window.innerHeight * 100;
        updateSheetHeight(newHeight);
    }

    // Determines whether to hide, set to fullscreen, or set to default
    // height based on the current height of the sheet content
    const dragStop = () => {

        if (!isDragging) return;
        else {
            isDragging = false;
            bottomSheet.classList.remove("dragging");
            const sheetHeight = parseInt(sheetContent.style.height);
            if (sheetHeight < 25) {
                closePopupButton();
                $('#familias').css('z-index', '1');
            } else if (sheetHeight > 60) {
                updateSheetHeight(100);
                $('#familias').css('z-index', '-1');
            } else {
                updateSheetHeight(40);
                $('#familias').css('z-index', '1');
            }

            // sheetHeight < 25 ? map.closePopup() : sheetHeight > 60 ? updateSheetHeight(100) : updateSheetHeight(40);
        }

    }

    const mediaQueryList = window.matchMedia("(min-width: 700px)");
    mediaQueryList.addEventListener("change", (event) => onMediaQueryChange(event));
    onMediaQueryChange(mediaQueryList);

    function onMediaQueryChange(event) {
        if (event.matches) {
            document.documentElement.style.setProperty("--min-width", "true");
            movil = false;
        } else {
            document.documentElement.style.removeProperty("--min-width");
            movil = true;
        }
    }

    function loadPopUp(id_cliente, id_emp, emp, direccion, nombre, familia) {
        openInfo = true;
        // closeNav();
        if (!movil) {
            if (navOpened) {
                document.getElementById("map").style.setProperty('width', 'calc(100% - 400px - 380px)');
                $('#familias').css('width', 'calc(100dvw - 390px - 420px)');
                $('#familias').css('justify-content', 'center');
            } else {
                document.getElementById("map").style.setProperty('width', 'calc(100% - 380px)');
                $('#familias').css('width', 'calc(100dvw - 390px)');
                $('#familias').css('justify-content', 'center');
            }
            // $('#familias').css('padding', '0');
            // $('#familias').css('justify-content', 'flex-start');
        }
        if (sheetContent !== null)
            updateSheetHeight(40);
        var logo = "";
        switch (parseInt(id_emp)) {
            case 1:
                logo = `<svg height="60" viewBox="0 0 131 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2410_4594)">
                        <path d="M43.4037 19.8514V18.5264H34.4365L28.9899 37.5648L23.5931 18.5264H18.047L26.4506 43.6075H31.3569L38.2422 23.2241V26.7608C39.1836 24.9372 41.176 21.4642 43.4004 19.8548L43.4037 19.8514Z" fill="#002C53"/>
                        <path d="M38.2455 36.1662V43.6075H43.4037V31.3514C42.26 32.1076 39.8566 33.8541 38.2455 36.1662Z" fill="#002C53"/>
                        <path d="M8.87102 18.5264H14.7452L23.5367 43.6075H17.9045L16.2635 38.4515H7.11405L5.43002 43.6075H0L8.87102 18.5264ZM8.52625 34.1285H14.8911L11.7518 24.2614L8.52625 34.1285Z" fill="#002C53"/>
                        <path d="M118.595 39.0972V18.5264H113.401V39.0972H100.661V32.6463H111.537V28.2932H100.661V22.9665H111.74V18.5264H95.5889V25.2216C95.2507 24.1744 94.7568 23.1873 94.1104 22.2538C93.0297 20.7113 91.6175 19.6507 89.8737 19.0718C88.8494 18.7305 87.5698 18.5499 86.025 18.5298H75.3141V43.6109H86.025C89.7842 43.6109 92.5589 42.0483 94.359 38.9333C94.8629 38.0499 95.2739 37.093 95.5855 36.0658V43.6142H131V39.1039H118.592L118.595 39.0972ZM90.2815 35.5907C89.3135 38.0299 87.603 39.2478 85.1498 39.2478H80.353V22.8828H85.1498C87.5101 22.8828 89.0914 23.5654 89.8837 24.9305C90.6793 26.299 91.0738 28.2564 91.0738 30.8093C91.0738 32.6563 90.8086 34.2523 90.2815 35.5907Z" fill="#002C53"/>
                        <path d="M75.8876 5.53419C75.8876 5.53419 72.3505 10.8341 65.6641 8.47192C61.5137 6.27364 65.0906 2.9344 65.8663 2.27191C66.8277 1.45216 70.9416 -1.15098 72.8246 0.588905C73.3284 1.05399 73.7362 1.65626 70.6897 4.29285C69.2377 5.58438 71.0444 7.32427 73.9749 5.7751C76.8987 4.23263 75.891 5.53754 75.891 5.53754L75.8876 5.53419Z" fill="#B1B3B4"/>
                        <path d="M80.3894 9.27828C80.3894 9.27828 73.6003 15.1838 79.3518 16.9672C81.3607 17.5862 83.2337 16.1475 83.5951 15.7493C84.9178 14.3005 83.9763 10.456 80.7342 12.8752C79.9287 13.4741 78.9673 12.9789 79.4977 12.1558C82.2658 7.86295 80.3894 9.27493 80.3894 9.27493V9.27828Z" fill="#B1B3B4"/>
                        <path d="M52.5962 43.9856C52.5962 43.9856 54.2703 45.2136 57.4693 43.4837C61.0032 41.5765 69.1382 35.5739 71.7836 26.9113C74.3594 18.4595 73.3881 13.5376 81.2215 6.96286C81.2215 6.96286 81.8481 6.31041 80.2204 6.13976C78.7352 6.09961 75.1948 6.6818 70.6333 11.9583C70.6333 11.9583 68.2995 14.779 67.2851 19.5369C66.3404 23.9669 66.1414 26.7808 64.4541 30.6521C59.0605 43.032 52.5929 43.9856 52.5929 43.9856H52.5962Z" fill="#B1B3B4"/>
                        <path d="M50.3984 15.7727C52.5797 14.6251 55.6096 13.9559 57.7246 14.4578C65.1171 16.2144 63.7911 24.2245 62.7038 28.4036C61.8617 31.6324 60.0352 35.3263 57.6019 37.9161C55.1753 40.5025 53.372 42.3863 48.9066 43.3934L48.595 43.1759C48.595 43.1759 53.1134 39.8902 54.8007 37.712C56.4848 35.5338 60.1943 29.1765 55.7886 25.8072C54.9532 25.1714 54.0648 24.8201 51.4691 25.4358C48.9663 26.0313 45.9927 27.5705 40.619 32.2113C38.8356 33.7471 36.3858 36.4372 35.5238 37.2134C35.5238 37.2134 40.3041 25.5161 51.0083 21.5378C51.0083 21.5378 47.0999 20.0789 39.0809 27.9519C39.0809 27.9519 40.745 20.8485 50.3951 15.7694L50.3984 15.7727Z" fill="#B1B3B4"/>
                        <path d="M62.6176 52.1698L61.8385 53.8896H60.3468L63.5425 47.2613H65.3458L66.1149 53.8896H64.6431L64.5071 52.1698H62.6176ZM64.4508 51.089L64.3248 49.6637C64.295 49.299 64.2552 48.7703 64.2287 48.3655H64.2088C64.043 48.7703 63.8574 49.2822 63.6817 49.6637L63.0286 51.089H64.4508Z" fill="#002C53"/>
                        <path d="M68.2797 49.0815L68.4951 51.1861C68.5449 51.775 68.6012 52.1397 68.6211 52.4943H68.6609C68.7769 52.1497 68.9327 51.785 69.188 51.1961L70.1526 49.0815H71.7207L69.0587 53.8896H67.6365L66.8277 49.0815H68.2797Z" fill="#002C53"/>
                        <path d="M71.5085 53.8896L72.4135 49.0815H73.8157L72.9074 53.8896H71.5052H71.5085ZM74.9197 47.0037L73.6434 48.4993H72.6091L73.524 47.0037H74.9163H74.9197Z" fill="#002C53"/>
                        <path d="M77.6081 53.7424C77.3065 53.8896 76.8192 54 76.2556 54C75.0755 54 74.2467 53.2706 74.2467 52.0226C74.2467 50.3128 75.5429 48.9744 77.3562 48.9744C77.764 48.9744 78.1153 49.0547 78.3209 49.1317L77.9794 50.2225C77.8137 50.1522 77.6181 50.1054 77.3363 50.1054C76.3319 50.1054 75.7186 50.9619 75.7186 51.8653C75.7186 52.5245 76.1263 52.8591 76.6534 52.8591C77.0612 52.8591 77.3562 52.762 77.5982 52.6616L77.6081 53.7424Z" fill="#002C53"/>
                        <path d="M83.2603 50.9485C83.2603 52.7085 82.0702 53.9967 80.416 53.9967C79.2093 53.9967 78.4004 53.2003 78.4004 52.0092C78.4004 50.3362 79.5508 48.9711 81.2447 48.9711C82.521 48.9711 83.2603 49.8577 83.2603 50.9485ZM79.8491 51.9824C79.8491 52.5445 80.1209 52.9159 80.6082 52.9159C81.3773 52.9159 81.8083 51.785 81.8083 50.9586C81.8083 50.5169 81.6226 50.0451 81.0591 50.0451C80.2502 50.0451 79.8392 51.2363 79.8491 51.9824Z" fill="#002C53"/>
                        <path d="M83.6813 53.8896L85.0073 46.9066H86.4095L85.0835 53.8896H83.6813Z" fill="#002C53"/>
                        <path d="M89.3798 53.8896C89.3897 53.6052 89.4196 53.2806 89.4494 52.936H89.4196C88.982 53.7323 88.3753 54 87.8217 54C86.9631 54 86.3797 53.3308 86.3797 52.3271C86.3797 50.7043 87.4405 48.9744 89.7909 48.9744C90.3577 48.9744 90.971 49.0815 91.3688 49.2086L90.8616 51.7248C90.7456 52.3438 90.6561 53.2907 90.676 53.8896H89.3798ZM89.7875 50.0819C89.6815 50.0619 89.5456 50.0418 89.4063 50.0418C88.5013 50.0418 87.8383 51.1627 87.8383 52.0594C87.8383 52.5312 88.024 52.8557 88.4118 52.8557C88.8295 52.8557 89.3467 52.3739 89.5621 51.1928L89.7842 50.0819H89.7875Z" fill="#002C53"/>
                        <path d="M99.0497 46.9066L98.0851 52.0225C97.969 52.6315 97.8795 53.3208 97.863 53.8929H96.5767L96.6464 53.1167H96.6265C96.2088 53.7557 95.622 54.0033 95.0684 54.0033C94.2595 54.0033 93.5866 53.3542 93.5866 52.1832C93.5866 50.5102 94.7866 48.9777 96.5867 48.9777C96.7823 48.9777 97.0342 49.0079 97.2397 49.0848L97.6475 46.91H99.0497V46.9066ZM97.0342 50.2124C96.9281 50.1254 96.7127 50.0451 96.4806 50.0451C95.6419 50.0451 95.0485 51.0288 95.0485 51.9523C95.0485 52.5311 95.3038 52.859 95.7115 52.859C96.1193 52.859 96.6066 52.4274 96.7922 51.5106L97.0342 50.2124Z" fill="#002C53"/>
                        <path d="M102.898 53.6152C102.342 53.8996 101.652 54 101.125 54C99.7625 54 99.0597 53.2137 99.0597 51.9657C99.0597 50.5002 100.111 48.9744 101.838 48.9744C102.802 48.9744 103.495 49.5165 103.495 50.4098C103.495 51.6378 102.315 52.0828 100.406 52.0326C100.406 52.1698 100.455 52.3873 100.551 52.5245C100.737 52.7721 101.059 52.8992 101.466 52.8992C101.984 52.8992 102.421 52.7821 102.802 52.5947L102.898 53.6186V53.6152ZM102.176 50.4098C102.176 50.1723 101.98 50.005 101.659 50.005C101.006 50.005 100.634 50.547 100.538 50.9987C101.629 51.0088 102.176 50.8515 102.176 50.4199V50.4098Z" fill="#002C53"/>
                        <path d="M106.896 47.2613H108.299L107.284 52.6516H109.807L109.575 53.8896H105.647L106.893 47.2613H106.896Z" fill="#002C53"/>
                        <path d="M110.337 53.8896L111.663 46.9066H113.066L111.74 53.8896H110.337Z" fill="#002C53"/>
                        <path d="M116.905 53.6152C116.348 53.8996 115.658 54 115.131 54C113.768 54 113.066 53.2137 113.066 51.9657C113.066 50.5002 114.117 48.9744 115.844 48.9744C116.808 48.9744 117.501 49.5165 117.501 50.4098C117.501 51.6378 116.321 52.0828 114.412 52.0326C114.412 52.1698 114.461 52.3873 114.557 52.5245C114.743 52.7721 115.065 52.8992 115.472 52.8992C115.99 52.8992 116.427 52.7821 116.808 52.5947L116.905 53.6186V53.6152ZM116.182 50.4098C116.182 50.1723 115.986 50.005 115.665 50.005C115.012 50.005 114.64 50.547 114.544 50.9987C115.635 51.0088 116.182 50.8515 116.182 50.4199V50.4098Z" fill="#002C53"/>
                        <path d="M117.803 53.8896L118.708 49.0815H120.11L119.202 53.8896H117.8H117.803ZM119.576 48.4792C119.195 48.4792 118.903 48.2149 118.903 47.79C118.913 47.3081 119.285 46.9836 119.712 46.9836C120.14 46.9836 120.415 47.2579 120.415 47.6829C120.415 48.1848 120.034 48.4792 119.586 48.4792H119.576Z" fill="#002C53"/>
                        <path d="M125.978 46.9066L125.013 52.0225C124.897 52.6315 124.808 53.3208 124.788 53.8929H123.501L123.571 53.1167H123.551C123.13 53.7557 122.547 54.0033 121.993 54.0033C121.184 54.0033 120.511 53.3542 120.511 52.1832C120.511 50.5102 121.711 48.9777 123.511 48.9777C123.707 48.9777 123.959 49.0079 124.164 49.0848L124.572 46.91H125.974L125.978 46.9066ZM123.962 50.2124C123.856 50.1254 123.641 50.0451 123.405 50.0451C122.567 50.0451 121.973 51.0288 121.973 51.9523C121.973 52.5311 122.225 52.859 122.636 52.859C123.047 52.859 123.535 52.4274 123.717 51.5106L123.962 50.2124Z" fill="#002C53"/>
                        <path d="M128.961 53.8896C128.971 53.6052 129.001 53.2806 129.031 52.936H129.001C128.563 53.7323 127.96 54 127.403 54C126.545 54 125.961 53.3308 125.961 52.3271C125.961 50.7043 127.022 48.9744 129.372 48.9744C129.936 48.9744 130.552 49.0815 130.95 49.2086L130.443 51.7248C130.327 52.3438 130.238 53.2907 130.257 53.8896H128.961ZM129.369 50.0819C129.263 50.0619 129.124 50.0418 128.988 50.0418C128.083 50.0418 127.42 51.1627 127.42 52.0594C127.42 52.5312 127.605 52.8557 127.993 52.8557C128.411 52.8557 128.928 52.3739 129.144 51.1928L129.369 50.0819Z" fill="#002C53"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_2410_4594">
                        <rect width="131" height="54" fill="white"/>
                        </clipPath>
                        </defs>
                        </svg>
                        `;
                break;
            case 2:
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="29.39 355.295 537.1 133.3">
                    <g>
                        <path fill="#002C51" d="M56.11,409.62h17.7l26.48,74.85H83.33l-4.94-15.39H50.82l-5.07,15.39H29.39L56.11,409.62z M55.08,456.19      h19.17l-9.46-29.45L55.08,456.19z"/>
                        <path fill="#002C51" d="M133.36,409.62h16.25l-25.52,74.85h-14.78L84,409.62h16.71l16.25,56.82L133.36,409.62z"/>
                        <path fill="#002C51" d="M160.61,484.47h-15.54v-74.85h15.54V484.47z"/>
                        <path fill="#002C51" d="M169.8,417.44c6.03-6.16,13.7-9.24,23.01-9.24c12.46,0,21.57,4.13,27.33,12.39      c3.18,4.64,4.89,9.29,5.12,13.96h-15.64c-1-3.59-2.27-6.3-3.83-8.12c-2.79-3.25-6.91-4.88-12.39-4.88      c-5.57,0-9.97,2.29-13.18,6.88c-3.22,4.59-4.83,11.08-4.83,19.47c0,8.4,1.7,14.68,5.09,18.87c3.39,4.18,7.71,6.27,12.94,6.27      c5.36,0,9.46-1.79,12.27-5.38c1.56-1.93,2.85-4.82,3.87-8.68h15.54c-1.34,8.16-4.76,14.79-10.27,19.91      c-5.51,5.11-12.57,7.67-21.18,7.67c-10.65,0-19.02-3.45-25.12-10.36c-6.1-6.94-9.14-16.45-9.14-28.54      C159.4,434.59,162.87,424.52,169.8,417.44z"/>
                    </g>
                    <g>
                        <path fill="#002C51" d="M397.98,461.33c0.48,3.42,1.43,5.98,2.85,7.67c2.59,3.08,7.03,4.62,13.33,4.62c3.77,0,6.83-0.41,9.18-1.22      c4.46-1.56,6.69-4.45,6.69-8.68c0-2.47-1.09-4.38-3.28-5.74c-2.19-1.32-5.65-2.49-10.39-3.5l-8.1-1.78      c-7.96-1.76-13.44-3.67-16.42-5.74c-5.04-3.45-7.57-8.85-7.57-16.2c0-6.7,2.47-12.27,7.4-16.71c4.93-4.43,12.18-6.65,21.75-6.65      c7.98,0,14.8,2.09,20.43,6.27c5.64,4.18,8.59,10.25,8.87,18.21h-15.03c-0.28-4.5-2.29-7.7-6.04-9.6      c-2.5-1.25-5.61-1.88-9.32-1.88c-4.13,0-7.43,0.81-9.89,2.44c-2.46,1.62-3.7,3.89-3.7,6.8c0,2.67,1.21,4.67,3.64,5.99      c1.56,0.88,4.88,1.91,9.94,3.1l13.14,3.1c5.76,1.35,10.07,3.17,12.95,5.43c4.46,3.52,6.69,8.62,6.69,15.29      c0,6.84-2.64,12.52-7.93,17.04s-12.75,6.78-22.4,6.78c-9.85,0-17.61-2.23-23.25-6.68c-5.65-4.45-8.47-10.57-8.47-18.36H397.98z"/>
                        <path fill="#002C51" d="M464.9,409.59h17.7l26.48,74.85h-16.96l-4.94-15.39h-27.56l-5.07,15.39h-16.36L464.9,409.59z       M463.87,456.15h19.17l-9.46-29.45L463.87,456.15z"/>
                        <path fill="#002C51" d="M505.76,409.59h16.4l29.71,52.19v-52.19h14.57v74.85h-15.64l-30.47-53.11v53.11h-14.57V409.59z"/>
                    </g>
                    <path fill="#002C51" d="M278.53,454.13c-0.06,0.05-0.13,0.12-0.2,0.17c-0.77,5.03-2.4,9.16-4.93,12.33     c-3.64,4.57-8.56,6.86-14.75,6.86s-11.14-2.29-14.83-6.86c-3.69-4.57-5.54-11.07-5.54-19.5s1.84-14.93,5.54-19.5     c3.69-4.57,8.63-6.86,14.83-6.86s11.11,2.29,14.75,6.88c0.14,0.18,0.27,0.39,0.41,0.58c2.09-3.55,5.07-7.72,9.27-12.07     c-5.65-5.76-13.79-8.64-24.43-8.64c-10.7,0-18.87,2.91-24.53,8.73c-7.58,6.87-11.38,17.16-11.38,30.88     c0,13.44,3.79,23.73,11.38,30.88c5.65,5.82,13.83,8.73,24.53,8.73c10.7,0,18.87-2.91,24.53-8.73     c7.55-7.14,11.32-17.43,11.32-30.88c0-1.8-0.07-3.53-0.2-5.21C290.04,444.77,284.89,448.68,278.53,454.13z"/>
                    <g>
                        <path fill="#8C9091" d="M383.64,371.9c0,0-10.72,15.91-30.97,8.82c-12.58-6.6-1.74-16.62,0.61-18.61      c2.91-2.46,15.37-10.28,21.08-5.05c1.53,1.39,2.76,3.2-6.48,11.11c-4.39,3.88,1.08,9.1,9.95,4.45      C386.7,367.98,383.64,371.9,383.64,371.9z"/>
                        <path fill="#8C9091" d="M397.29,383.13c0,0-20.56,17.72-3.14,23.08c6.08,1.86,11.75-2.46,12.86-3.65c4-4.35,1.16-15.88-8.67-8.63      c-2.44,1.8-5.35,0.31-3.75-2.16C402.96,378.89,397.29,383.13,397.29,383.13z"/>
                        <g>
                            <path fill="#8C9091" d="M313.08,487.31c0,0,5.07,3.69,14.77-1.51c10.7-5.72,35.35-23.74,43.36-49.74       c7.81-25.37,4.86-40.14,28.59-59.88c0,0,1.89-1.96-3.04-2.47c-4.5-0.12-15.23,1.62-29.04,17.47c0,0-7.07,8.46-10.14,22.75       c-2.86,13.3-3.46,21.74-8.57,33.37C332.68,484.45,313.08,487.31,313.08,487.31z"/>
                            <path fill="#8C9091" d="M306.42,402.63c6.61-3.44,15.79-5.46,22.19-3.95c22.4,5.27,18.38,29.31,15.08,41.86       c-2.55,9.69-8.08,20.78-15.45,28.56c-7.35,7.76-12.81,13.41-26.34,16.44l-0.94-0.65c0,0,13.69-9.86,18.79-16.4       c5.1-6.54,16.35-25.63,3-35.74c-2.53-1.91-5.22-2.96-13.09-1.11c-7.58,1.78-16.59,6.41-32.87,20.34       c-5.4,4.61-12.82,12.69-15.44,15.02c0,0,14.49-35.12,46.92-47.05c0,0-11.84-4.38-36.13,19.26       C272.14,439.19,277.19,417.88,306.42,402.63z"/>
                        </g>
                    </g>
                </svg>`;
                break;
            case 3:
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="77.3697 355.96 441.2 130.9">
                    <g xmlns="http://www.w3.org/2000/svg">
                        <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="77.3678" y1="421.4183" x2="208.2885" y2="421.4183">
                            <stop offset="0" style="stop-color:#C6C5BA"/>
                            <stop offset="1" style="stop-color:#E5231D"/>
                        </linearGradient>
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="url(#SVGID_1_)" d="M104.57,386.9c-1.7,17.37-2.06,34.85-1.29,52.29c18.01,0.89,36.05,1.11,54.08,0.82     c-0.18,9.46-0.48,18.92-0.97,28.37c9.1-0.43,18.18-1.25,27.19-2.59c1.36-8.92,2.2-17.91,2.69-26.92     c-9.6,0.56-19.22,0.89-28.84,1.07c0.31-18.21,0.17-36.42-0.52-54.62C139.45,384.72,121.97,385.16,104.57,386.9z M177.01,379.59     c0.73,6.23,1.27,12.47,1.65,18.73c5.75,0.37,11.5,0.84,17.24,1.45c-0.53-5.92-1.28-11.82-2.29-17.68     C188.11,381.03,182.57,380.23,177.01,379.59z M77.37,421.21c0.03-11.46,3.21-22.35,8.7-31.83c6.12-1.12,12.3-1.94,18.49-2.59     c0.7-6.72,1.6-13.41,2.86-20.05c10.34-6.82,22.61-10.82,35.59-10.78c35.29,0.1,65.16,29.97,65.28,65.26     c0.11,35.44-29.83,65.57-65.27,65.66c-3.75,0.01-7.45-0.31-11.05-0.95c-0.58-7.87-0.95-15.75-1.24-23.63     c-11.08-0.38-22.14-1.23-33.12-2.78c0.48,3.42,1.02,6.84,1.66,10.24C85.87,457.63,77.31,440.19,77.37,421.21z"/>
                        <path fill="#E5231D" d="M517.42,368.12v59.24c0,6.21,0.44,9.32,1.11,11.76h-21.52c0.67-2.44,1.11-5.55,1.11-11.76v-47.48     c0-6.21-0.44-9.32-1.11-11.76H517.42z M271.83,395.19h-17.08c-2.55,0-3.33,1-3.33,3.77v28.18c0,2.77,0.78,3.77,3.33,3.77h17.08     c2.55,0,3.33-1,3.33-3.77v-28.18C275.16,396.19,274.38,395.19,271.83,395.19z M250.31,386.98l1.11,4.88h0.22     c0.78-2.77,2.66-4.88,5.54-4.88h24.18c8.88,0,13.09,4.33,13.09,13.98v24.18c0,9.65-4.21,13.98-13.09,13.98h-23.29     c-1.55,0-2.77-0.22-3.66-1.11c-1-0.78-1.66-2.11-2.55-4.22h-0.45v13.31c0,6.21,0.45,9.32,1.11,11.76h-20.41v-60.12     c0-6.21-0.44-9.32-1.11-11.76H250.31z M345.04,411.16h-20.41c-2.55,0-3.33,1-3.33,3.77v12.2c0,2.77,0.78,3.77,3.33,3.77h17.08     c2.55,0,3.33-1,3.33-3.77V411.16z M364.34,400.96v26.4c0,6.21,0.45,9.32,1.11,11.76h-17.86l-0.89-5.43h-0.22     c-1.66,4.22-3.55,5.43-7.43,5.43h-23.96c-9.1,0-13.09-3.77-13.09-18.08c0-14.31,4-18.08,13.09-18.08h27.18     c1.77,0,2.77-0.67,2.77-2.44v-3.33c0-1.77-1-2.44-2.77-2.44H313.2c-3.22,0-5.21,0.11-9.32,1.11l1.22-8.87h46.15     C360.13,386.98,364.34,391.31,364.34,400.96z M416.14,386.98v7.77H397.4v30.84c0,4.33,1.33,5.32,4.77,5.32h7.88     c5.43,0,9.76-0.55,10.87-1.22l-1.77,9.43h-28.4c-7.76,0-12.65-4.55-12.65-14.75v-29.62H368v-7.77h10.09v-10.65l19.3-2.55v13.2     H416.14z M444.54,409.61h23.74v-10.65c0-2.77-0.78-3.77-3.33-3.77h-17.08c-2.55,0-3.33,1-3.33,3.77V409.61z M487.58,417.82     h-43.04v9.32c0,2.77,0.78,3.77,3.33,3.77h33.61c1.66,0,3.77-0.22,6.1-0.67l-1.55,8.87h-47.7c-8.87,0-13.09-4.32-13.09-13.98     v-24.18c0-9.65,4.22-13.98,13.09-13.98h36.16c8.88,0,13.09,4.33,13.09,13.98V417.82z"/>
                    </g>
                </svg>`;
                break;
            case 4:
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="211.236 57.37 1181 623.5">
                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#b41015" d="M 1368.96 325.67 v 48.08 h 21.14 v 49.72 h -21.14 c 0 9.99 1.55 16.87 4.66 20.65 c 3.18 3.77 8.97 5.66 17.38 5.66 v 49.47 l -5.41 0.12 c -10.66 0 -20.73 -2.48 -30.22 -7.43 c -9.39 -4.95 -16.95 -11.66 -22.66 -20.14 c -7.2 -10.83 -10.81 -25.17 -10.81 -43.05 V 325.67 H 1368.96 L 1368.96 325.67 z M 1299.62 497.48 h -47.08 v -70.87 c 0 -7.21 -2.97 -10.82 -8.94 -10.82 c -5.86 0 -8.8 3.61 -8.8 10.82 v 70.87 h -47.07 v -69.99 c 0 -16.36 5.4 -30.12 16.23 -41.28 c 10.83 -11.24 24.08 -16.86 39.78 -16.87 c 17.95 0 32.33 6.51 43.16 19.51 c 8.47 10.15 12.7 24.29 12.72 42.42 V 497.48 L 1299.62 497.48 z M 1168.22 442.34 h -71.37 c -1.1 -3.6 -1.63 -6.92 -1.63 -9.94 c 0 -4.19 0.76 -8.3 2.27 -12.34 h 23.91 c -1.68 -9.31 -6.93 -13.97 -15.75 -13.97 c -5.86 0 -10.66 2.51 -14.34 7.55 c -3.7 4.95 -5.54 11.33 -5.54 19.13 c 0 8.14 1.75 14.73 5.28 19.76 c 3.61 5.04 8.3 7.55 14.1 7.55 c 4.69 0 9.23 -2.35 13.6 -7.05 l 26.82 32.22 c -12.51 9.82 -26.4 14.72 -41.67 14.72 c -18.63 0 -34.28 -6.25 -46.95 -18.75 c -12.67 -12.5 -19.01 -27.99 -19.01 -46.45 c 0 -18.38 6.38 -33.89 19.13 -46.57 c 12.84 -12.67 28.57 -19 47.2 -19.01 c 18.2 0 33.47 6.21 45.81 18.63 c 12.43 12.33 18.63 27.56 18.63 45.69 C 1168.72 435.38 1168.54 438.32 1168.22 442.34 L 1168.22 442.34 z M 1031.02 370.73 v 50.35 c -3.27 -1.85 -6.25 -2.77 -8.94 -2.77 c -8.56 0 -12.84 6.55 -12.84 19.64 v 59.54 h -47.07 v -68.6 c 0 -18.13 4.95 -32.56 14.85 -43.3 c 9.9 -10.83 23.12 -16.24 39.64 -16.24 C 1020.36 369.34 1025.14 369.8 1031.02 370.73 L 1031.02 370.73 z M 948.32 370.73 v 50.35 c -3.27 -1.85 -6.25 -2.77 -8.93 -2.77 c -8.56 0 -12.84 6.55 -12.84 19.64 v 59.54 h -47.07 v -68.6 c 0 -18.13 4.95 -32.56 14.85 -43.3 c 9.9 -10.83 23.12 -16.24 39.65 -16.24 C 937.66 369.34 942.44 369.8 948.32 370.73 L 948.32 370.73 z M 793.76 415.79 c -5.04 0 -9.31 1.85 -12.84 5.54 c -3.53 3.61 -5.29 8.06 -5.29 13.34 c 0 5.2 1.76 9.65 5.29 13.34 c 3.61 3.7 7.89 5.54 12.84 5.54 c 5.04 0 9.31 -1.85 12.84 -5.54 c 3.61 -3.69 5.41 -8.14 5.42 -13.34 c 0 -5.29 -1.76 -9.73 -5.29 -13.34 C 803.2 417.64 798.88 415.79 793.76 415.79 L 793.76 415.79 z M 795.02 369.34 c 17.78 0 33.02 6.42 45.69 19.26 c 12.75 12.76 19.13 28.07 19.13 45.94 c 0 18.13 -6.46 33.57 -19.38 46.32 c -12.84 12.75 -28.36 19.12 -46.57 19.13 c -18.21 0 -33.77 -6.37 -46.7 -19.13 c -12.92 -12.84 -19.38 -28.28 -19.38 -46.32 c 0 -18.37 6.46 -33.81 19.38 -46.31 C 760.11 375.64 776.05 369.34 795.02 369.34 L 795.02 369.34 z M 691.18 325.67 v 48.08 h 21.15 v 49.72 H 691.18 c 0 9.99 1.55 16.87 4.66 20.65 c 3.19 3.77 8.97 5.66 17.37 5.66 v 49.47 l -5.42 0.12 c -10.66 0 -20.73 -2.48 -30.21 -7.43 c -9.4 -4.95 -16.95 -11.66 -22.66 -20.14 c -7.22 -10.83 -10.83 -25.17 -10.83 -43.05 V 325.67 H 691.18 L 691.18 325.67 z M284.33,63.29c22.83-0.16,25.78,11.05,25.78,11.05s7.36-15.47,28.72-15.47c21.36,0,24.31,15.47,24.31,15.47    s2.94-10.31,28.72-10.31c0,0,39.59,3.98,13.99,61.13c0,0-18.41,40.51-67.02,41.98c0,0-58.18,2.21-73.65-51.56    C265.18,115.58,248.62,63.55,284.33,63.29L284.33,63.29z M476.43,414.76c0,0-89.86,1.4-93.36-91.96h93.36V414.76L476.43,414.76z     M312.16,211.53c0-4.46,3.61-8.07,8.07-8.07c4.46,0,8.07,3.61,8.07,8.07c0,4.46-3.61,8.08-8.07,8.08    C315.78,219.6,312.16,215.98,312.16,211.53L312.16,211.53z M245.49,178.5c0,0,127.41-41.24,192.96,90.59    c0,0,31.67-118.58,153.93-118.58v404.64c0,0-5.04,124.25-138.41,124.25c-133.37,0-143.2-117.94-143.2-117.94V431.6    c0,0-42.12,55.46-80.02,18.25c-37.91-37.2-4.21-70.9-4.21-70.9s38.61-53.35,51.24-64.58c12.64-11.23,31.59-31.59,16.14-51.25    c-15.44-19.66-52.64-0.7-52.64-0.7l30.89-42.82L245.49,178.5L245.49,178.5z"/>
                </svg>`;
                break;
            default:
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="183.96 485.28 227.9 100.3">
                    <g>'
                        <path fill="#888B8D" d="M275.97,583.81h-83.86c-4.49,0-8.15-3.65-8.15-8.14v-82.24c0-4.49,3.65-8.15,8.15-8.15h83.86     c4.49,0,8.14,3.65,8.14,8.15v82.24C284.12,580.15,280.46,583.81,275.97,583.81z M192.12,488.74c-2.58,0-4.68,2.1-4.68,4.67v82.24     c0,2.58,2.1,4.67,4.68,4.67h83.86c2.58,0,4.67-2.1,4.67-4.67v-82.24c0-2.58-2.1-4.67-4.67-4.67H192.12z"/>'
                        <path fill="#003B71" d="M255.12,499.47c0,0-3.84-0.02-6.17,2.31c-2.33,2.33-33.07,33.07-33.07,33.07v-21.63     c0-15.05-19.87-13.74-19.87-13.74v55.24v13.69l19.87-19.88l49.05-49.05C264.93,499.47,255.12,499.47,255.12,499.47z"/>'
                        <path fill="#003B71" d="M248.96,548.48h-14.03c0,0,27.96-27.97,30.29-30.29c2.32-2.32,2.3-6.17,2.3-6.17v-9.81     c0,0-44.1,44.1-47.42,47.41c-6.4,6.4-2.76,18.72,7.68,18.72h42.51v-0.01C267.72,560.71,260.66,548.54,248.96,548.48z"/>'
                        <path fill="#888B8D" d="M270.03,544.72h-17.99c13.13,2.96,19.44,11.63,22.71,24.3v-19.37C274.75,549.65,275.01,544.72,270.03,544.72     z"/>'
                        <path fill="#888B8D" d="M409.96,584.69v-6.35h-1.9v6.35H409.96z M406.17,584.35v-5.66c0-0.69,1.28-1.25,2.85-1.25     c1.56,0,2.85,0.56,2.85,1.25v5.66c0,0.69-1.28,1.25-2.85,1.25C407.45,585.59,406.17,585.03,406.17,584.35z M297.3,584.35v-5.66     c0-0.69,1.28-1.25,2.85-1.25c1.56,0,2.85,0.56,2.85,1.25v0.91h-1.9v-1.26h-1.9v6.35h1.9v-2.76h-0.82v-0.83h2.72v3.24     c0,0.69-1.28,1.25-2.85,1.25C298.58,585.59,297.3,585.03,297.3,584.35z M324.63,585.53v-8.02h3.79c1.05,0,1.9,0.37,1.9,0.83v2.54     c0,0.38-0.38,0.53-0.85,0.64c0.46,0.08,0.85,0.25,0.85,0.74v2.67c0,0.22,0.2,0.39,0.46,0.6h-1.95c-0.21-0.17-0.41-0.39-0.41-0.6     v-3h-1.9v3.59H324.63z M326.53,578.34v2.76h1.9v-2.76H326.53z M357.66,577.51v6.84c0,0.69-1.28,1.25-2.85,1.25     c-1.56,0-2.85-0.56-2.85-1.25v-6.84h1.9v7.19h1.9v-7.19H357.66z M379.3,585.53v-8.02h3.8c1.05,0,1.9,0.37,1.9,0.83v2.76     c0,0.46-0.85,0.83-1.9,0.83h-1.9v3.59H379.3z M381.2,578.34v2.76h1.9v-2.76H381.2z"/>'
                        <path fill="#003B71" d="M409.25,558.54v-2.57h-2.57v6.09h2.57c1.43,0,2.57,1.15,2.57,2.58v5.01c0,2.12-1.74,3.86-3.86,3.86     c-2.12,0-3.86-1.74-3.86-3.86v-1.5h2.57v2.57h2.57v-6.09h-2.57c-1.43,0-2.57-1.15-2.57-2.58v-5.01c0-2.12,1.74-3.86,3.86-3.86     c2.12,0,3.86,1.74,3.86,3.86v1.5H409.25z M298.56,573.29l-2.99-24.84h2.61l1.15,12.21c0.14,1.57,0.31,3.1,0.42,6.26     c0.18-3.17,0.35-4.7,0.49-6.26l1.15-12.21h2.61L301,573.29H298.56z M313.31,555.97v17.32h-5.15c-1.43,0-2.58-1.15-2.58-2.57v-9.5     c0-1.43,1.15-2.57,2.58-2.57h2.57v-2.68h-4.59v-2.57h4.59C312.16,553.4,313.31,554.54,313.31,555.97z M308.16,561.22v9.5h2.57     v-9.5H308.16z M315.57,573.29v-25.05c0.7,0.04,1.32,0.31,1.81,0.77c0.49,0.45,0.76,1.11,0.76,1.81v22.47H315.57z M320.38,573.29     v-25.05c0.7,0.04,1.32,0.31,1.81,0.77s0.76,1.11,0.76,1.81v22.47H320.38z M330.01,569.64v-17.53c0-2.12,1.74-3.86,3.86-3.86     c2.12,0,3.86,1.74,3.86,3.86v2.82h-2.57v-3.9h-2.58v19.69h2.58v-3.9h2.57v2.82c0,2.12-1.74,3.86-3.86,3.86     C331.75,573.5,330.01,571.76,330.01,569.64z M339.36,569.64v-12.59c0-2.12,1.74-3.86,3.86-3.86c2.12,0,3.86,1.74,3.86,3.86v12.59     c0,2.12-1.74,3.86-3.86,3.86C341.1,573.5,339.36,571.76,339.36,569.64z M341.93,555.97v14.75h2.58v-14.75H341.93z M349.34,573.29     v-19.9h10.29c1.43,0,2.58,1.15,2.58,2.57v17.32h-2.58v-17.32h-2.57v17.32h-2.58v-17.32h-2.57v17.32H349.34z M364.46,578.16V553.4     h5.15c1.42,0,2.57,1.15,2.57,2.57v14.75c0,1.43-1.15,2.57-2.57,2.57h-2.58v4.87H364.46z M367.03,555.97v14.75h2.58v-14.75H367.03     z M382.16,555.97v17.32h-5.15c-1.43,0-2.57-1.15-2.57-2.57v-9.5c0-1.43,1.15-2.57,2.57-2.57h2.57v-2.68h-4.59v-2.57h4.59     C381.01,553.4,382.16,554.54,382.16,555.97z M377.01,561.22v9.5h2.57v-9.5H377.01z M384.41,573.29v-19.9h5.15     c1.43,0,2.57,1.15,2.57,2.57v17.32h-2.57v-17.32h-2.57v17.32H384.41z M402.11,553.4v24.77h-2.57v-4.87h-2.58     c-1.42,0-2.57-1.15-2.57-2.57V553.4h2.57v17.32h2.58V553.4H402.11z"/>'
                    </g>
                </svg>`;
                break;
        }
        let fam = familia.split(',');
        let familias = "";
        fam.forEach(element => {
            familias += '<div class="fam-pills selected d-flex gap-1 align-items-center" onclick="filterProducts(this)"><div class="fam_filter">' + element + '</div><span class="fam-pills-info selected">&check;</span></div>';
        });
        let content =
            '<div class="drag-icon"><span></span></div><div class="closePopUpButton" onclick="closePopupButton()"><span>X</span></div> <div class="popup-datos"><div>' +
            logo + '</div><h2 class="mt-5">' + nombre + '</h2><div class="d-flex gap-1 align-items-center"><div><span style="border:1px solid #003B71;padding:8px;border-radius:50%"><svg height="24" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path id="location_on" d="M6.00133 7.89552C6.37446 7.89552 6.69346 7.76265 6.95833 7.49689C7.22333 7.23114 7.35583 6.91171 7.35583 6.53858C7.35583 6.16546 7.22296 5.8464 6.95721 5.58139C6.69146 5.31652 6.37196 5.18408 5.99871 5.18408C5.62558 5.18408 5.30658 5.31696 5.04171 5.58271C4.77671 5.84846 4.64421 6.16796 4.64421 6.54121C4.64421 6.91433 4.77708 7.23333 5.04283 7.49821C5.30858 7.76308 5.62808 7.89552 6.00133 7.89552ZM6.00002 13.6317C7.46727 12.3182 8.59008 11.0583 9.36846 9.85208C10.1468 8.64583 10.536 7.58933 10.536 6.68258C10.536 5.31533 10.1016 4.19133 9.23289 3.31058C8.36414 2.42983 7.28652 1.98946 6.00002 1.98946C4.71352 1.98946 3.63589 2.42983 2.76714 3.31058C1.89839 4.19133 1.46402 5.31533 1.46402 6.68258C1.46402 7.58933 1.85321 8.64583 2.63158 9.85208C3.40996 11.0583 4.53277 12.3182 6.00002 13.6317ZM6.00002 15.1287C4.11252 13.4932 2.69714 11.9711 1.75389 10.5625C0.810645 9.15377 0.33902 7.86046 0.33902 6.68258C0.33902 4.95183 0.898832 3.55064 2.01846 2.47902C3.13821 1.40739 4.46539 0.871582 6.00002 0.871582C7.53464 0.871582 8.86183 1.40739 9.98158 2.47902C11.1012 3.55064 11.661 4.95183 11.661 6.68258C11.661 7.86046 11.1894 9.15377 10.2461 10.5625C9.30289 11.9711 7.88752 13.4932 6.00002 15.1287Z" fill="#003B71"/></svg></span></div><p class="p-0 m-0" style="font-size: 1rem;line-height: 150%;">' + direccion +
            '</p></div><div class="popup-prods"><h3 class="mt-4 pb-1 mb-3" style="color:#003B71; border-bottom:1px solid">Productos</h3><div class="d-flex justify-content-center"><div class="prod_load spinner-border text-secondary" role="status"></div></div><div id="fam_'+id_cliente+'" style="display:none !important" class="d-flex gap-1 flex-wrap">'+familias+'</div><ul id="ul_' +
            id_cliente + '" class="mt-4" style="max-height:310px;overflow-y:auto"></ul></div>';
        $('#info-panel').empty();
        $('#info-panel').append(content);


        if (!movil) {
            $('#info-panel').css('padding', '1.5em 3em 1em');
            $('#info-panel').css('width', '380px');
        } else {
            $('#info-panel').css('padding', '3em 3em 1em');
            $('#info-panel').css('width', '100dvw');
            $('#info-panel').css('height', '40dvh');
        }
        let prods = "";
        if (xhr != null) {
            xhr.abort();
        }
        xhr = $.ajax({
            url: "{{ route('getClientProdsLocation') }}",
            type: 'GET',
            dataType: "json",
            data: {
                id_empresa: id_emp,
                id_cliente: id_cliente
            },
            beforeSend: function() {
                $('.prod_load').show();
            },
            success: function(response) {
                if (response.length == 0) {
                    prods = '<li>No hay productos</li>'
                }else{
                    response.forEach(element => {
                        prods += `<li class="prod_fam prod_fam_${element.familia}">${element.name_cons}</li>`;
                    });
                }
            },
            statusCode: {
                403: function() {
                    showBootstrapModal("Intenta iniciar sesión nuevamente")
                }
            },
            complete: function(xhr, status) {
                $('.prod_load').hide();
                $('#ul_' + id_cliente).append(prods);
                $('#fam_' + id_cliente).css('display', 'flex');
            },
        });
    }

    function closePopupButton() {
        if (currentMarkerAnimated !== undefined)
            currentMarkerAnimated.content.classList.remove("animation");
        openInfo = false;
        $('#familias').css('z-index', '1');
        $('#info-panel').css('padding', '');
        if (!movil) {
            if (navOpened) {
                document.getElementById("familias").style.width = "calc(100dvw - 420px)";
                document.getElementById("familias").style.justifyContent = "center";
                document.getElementById("map").style.setProperty('width', 'calc(100% - 400px)');
            } else {
                $('#familias').css('width', '');
                $('#familias').css('padding', '');
                $('#familias').css('justify-content', 'center');
                document.getElementById("map").style.setProperty('width', "100%")
            }
            $('#info-panel').css('width', '0');
        } else {
            $('#info-panel').css('height', '0');
        }
    }

    function openNav() {
        // closePopupButton();
        $('#familias').css('z-index', '1');
        navOpened = true;
        $('.closebtn').show(350);
        if (!movil) {
            document.getElementById("mySidepanel").style.width = "400px";
            if (openInfo) {
                document.getElementById("familias").style.width = "calc(100dvw - 420px - 390px)";
                document.getElementById("familias").style.justifyContent = "";
                document.getElementById("map").style.setProperty('width', 'calc(100% - 400px - 380px)');
            } else {
                document.getElementById("familias").style.width = "calc(100dvw - 420px)";
                document.getElementById("familias").style.justifyContent = "center";
                document.getElementById("map").style.setProperty('width', 'calc(100% - 400px)');
            }
            document.getElementById("familias").style.left = "420px";
            document.getElementById("familias").style.padding = "1rem";
            document.getElementById("map").style.setProperty('transform', 'translateX(400px)');
        } else
            document.getElementById("mySidepanel").style.width = "calc(100dvw - 38px)";
    }

    function closeNav() {
        navOpened = false;
        $('.closebtn').hide();
        document.getElementById("mySidepanel").style.width = "0";
        document.getElementById("map").style.width = "100%";
        document.getElementById("map").style.setProperty('transform', 'translateX(0px)');
        if (!movil) {
            if (openInfo) {
                document.getElementById("familias").style.width = "calc(100dvw - 390px)";
                document.getElementById("familias").style.justifyContent = "center";
            } else {
                document.getElementById("familias").style.width = "";
                document.getElementById("familias").style.justifyContent = "center";
            }
            document.getElementById("familias").style.left = "";
        }
    }

    function toggleDropdown() {
        const dropdown = document.querySelector('.multiselect-options');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.querySelector('.multiselect-container');
        if (!container.contains(event.target)) {
            document.querySelector('.multiselect-options').style.display = 'none';
        }else{
            const checkedCheckboxes = document.querySelectorAll('.multiselect-options input[type="checkbox"]:checked');
            const sel = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
            $('.familia').removeClass('selected');
            sel.forEach(element => {
                $('#f_'+element).addClass('selected');
            });
            $('#sFamilias').val(sel);
            $('#sFamilias').trigger('change');
            if (sel.length == 0) {
                table.draw();
            }
        }
    });

    function filterProducts(e){
        $(e).toggleClass('selected');
        $(e).find('.fam-pills-info').toggleClass('selected');
        let filterProd = $('.fam-pills.selected>.fam_filter').map(function() {
                return this.innerText;
        }).get();
        $('.prod_fam').hide();
        filterProd.forEach(value => {
            console.log(value);
            $('.prod_fam_' + value.trim()).show();
        });

    }

    function showBootstrapModal(message, title = 'Aviso', type = 'warning',  redirectUrl = null) {
        // type: 'success', 'danger', 'warning', 'info', etc.
        $('#customBootstrapModalMsg').html(message);
        const modal = new bootstrap.Modal(document.getElementById('customBootstrapModal'));
        $('#custom-modal-header').removeClass('alert-success bg-danger bg-warning bg-info')
            .addClass('bg-' + type);
        $('#custom-modal-title').text(title);
        modal.show();

        if (redirectUrl) {
            $('#customBootstrapModal').off('hidden.bs.modal').on('hidden.bs.modal', function () {
                window.location.href = redirectUrl;
            });
        }
    }

    // Versión mejorada con zoom más notorio y debug
    async function animateMarkerTransition(targetMark, callback) {
        const transitionZoom = 15;
        const finalZoom = 17;

        const targetPos = {
            lat: +targetMark.info.latitud,
            lng: +targetMark.info.longitud,
        };

        console.log('=== INICIANDO TRANSICIÓN ===');

        // Preparar el panel del popup ANTES de la animación
        preparePopupPanel();

        // Iniciar la carga de datos en paralelo
        const dataPromise = preloadPopupData(
            targetMark.info.id_cliente,
            targetMark.info.id_empresa,
            targetMark.info.Empresa,
            targetMark.info.direccion,
            targetMark.info.nombre,
            targetMark.info.familia
        );

        try {
            // Paso 1: Zoom out suave por pasos
            console.log('Paso 1: Zoom out');
            await smoothZoomBySteps(map, transitionZoom, 80); // 80ms entre pasos

            // Paso 2: Mover al nuevo punto
            console.log('Paso 2: Paneo');
            await smoothPanBySteps(map, targetPos, 15, 50); // 15 pasos, 50ms cada uno

            // Paso 3: Zoom in suave por pasos
            console.log('Paso 3: Zoom in');
            await smoothZoomBySteps(map, finalZoom, 80);

            // Esperar datos si aún no están listos
            console.log('Paso 4: Esperando datos...');
            const popupContent = await dataPromise;

            // Mostrar el popup
            displayPopup(popupContent);
            console.log('=== TRANSICIÓN COMPLETADA ===');

            if (callback) callback();
        } catch (error) {
            console.error('Error en animación:', error);
        }
    }

    // Función para hacer zoom suave por pasos discretos
    function smoothZoomBySteps(map, targetZoom, stepDelay) {
        return new Promise((resolve) => {
            const currentZoom = map.getZoom();
            const step = currentZoom < targetZoom ? 0.5 : -0.5; // Pasos de 0.5 niveles
            let zoom = currentZoom;

            console.log(`Zoom de ${currentZoom} a ${targetZoom}, paso: ${step}`);

            function nextStep() {
                // Calcular el próximo nivel de zoom
                if (step > 0 && zoom >= targetZoom) {
                    map.setZoom(targetZoom);
                    console.log('Zoom completado:', targetZoom);
                    resolve();
                    return;
                } else if (step < 0 && zoom <= targetZoom) {
                    map.setZoom(targetZoom);
                    console.log('Zoom completado:', targetZoom);
                    resolve();
                    return;
                }

                zoom += step;
                map.setZoom(zoom);

                setTimeout(nextStep, stepDelay);
            }

            nextStep();
        });
    }

    // Función para hacer paneo suave por pasos
    function smoothPanBySteps(map, targetPos, steps, stepDelay) {
        return new Promise((resolve) => {
            const startPos = map.getCenter();
            const startLat = startPos.lat();
            const startLng = startPos.lng();
            const latStep = (targetPos.lat - startLat) / steps;
            const lngStep = (targetPos.lng - startLng) / steps;

            let currentStep = 0;

            console.log(`Pan de (${startLat.toFixed(4)}, ${startLng.toFixed(4)}) a (${targetPos.lat.toFixed(4)}, ${targetPos.lng.toFixed(4)}) en ${steps} pasos`);

            function nextStep() {
                if (currentStep >= steps) {
                    map.setCenter(targetPos);
                    console.log('Pan completado');
                    resolve();
                    return;
                }

                currentStep++;
                const newLat = startLat + (latStep * currentStep);
                const newLng = startLng + (lngStep * currentStep);

                map.setCenter({ lat: newLat, lng: newLng });

                setTimeout(nextStep, stepDelay);
            }

            nextStep();
        });
    }

    // Animar zoom con mejor implementación
    function animateZoomTo(map, targetZoom, duration) {
        return new Promise((resolve) => {
            const startZoom = map.getZoom();
            const zoomDiff = targetZoom - startZoom;

            console.log('animateZoomTo: de', startZoom, 'a', targetZoom, 'diferencia:', zoomDiff);

            if (Math.abs(zoomDiff) < 0.1) {
                console.log('Zoom ya está en el nivel target, resolviendo inmediatamente');
                resolve();
                return;
            }

            const startTime = Date.now(); // Usar Date.now() en lugar de performance.now()
            let frameCount = 0;

            function updateZoom() {
                frameCount++;
                const currentTime = Date.now();
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);

                // Easing function para suavidad (ease-in-out)
                const easeProgress = progress < 0.5
                    ? 2 * progress * progress
                    : 1 - Math.pow(-2 * progress + 2, 2) / 2;

                const newZoom = startZoom + (zoomDiff * easeProgress);

                if (frameCount % 10 === 0) { // Log cada 10 frames
                    console.log('Zoom frame:', frameCount, 'progress:', progress.toFixed(2), 'zoom:', newZoom.toFixed(2));
                }

                map.setZoom(newZoom);

                if (progress < 1) {
                    requestAnimationFrame(updateZoom);
                } else {
                    console.log('Zoom completado en', frameCount, 'frames');
                    resolve();
                }
            }

            requestAnimationFrame(updateZoom);
        });
    }

    // Animar paneo con mejor implementación
    function animatePanTo(map, targetPos, duration) {
        return new Promise((resolve) => {
            const startPos = map.getCenter();
            const startLat = startPos.lat();
            const startLng = startPos.lng();
            const latDiff = targetPos.lat - startLat;
            const lngDiff = targetPos.lng - startLng;

            console.log('animatePanTo: de', startLat.toFixed(4), startLng.toFixed(4),
                        'a', targetPos.lat.toFixed(4), targetPos.lng.toFixed(4));

            if (Math.abs(latDiff) < 0.0001 && Math.abs(lngDiff) < 0.0001) {
                console.log('Ya está en la posición target, resolviendo inmediatamente');
                resolve();
                return;
            }

            const startTime = Date.now();
            let frameCount = 0;

            function updatePan() {
                frameCount++;
                const currentTime = Date.now();
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);

                // Easing function para suavidad (ease-in-out)
                const easeProgress = progress < 0.5
                    ? 2 * progress * progress
                    : 1 - Math.pow(-2 * progress + 2, 2) / 2;

                const newLat = startLat + (latDiff * easeProgress);
                const newLng = startLng + (lngDiff * easeProgress);

                if (frameCount % 10 === 0) { // Log cada 10 frames
                    console.log('Pan frame:', frameCount, 'progress:', progress.toFixed(2));
                }

                map.setCenter({ lat: newLat, lng: newLng });

                if (progress < 1) {
                    requestAnimationFrame(updatePan);
                } else {
                    console.log('Pan completado en', frameCount, 'frames');
                    resolve();
                }
            }

            requestAnimationFrame(updatePan);
        });
    }

    // Preparar el panel visualmente
    function preparePopupPanel() {
        const infoPanel = $('#info-panel');

        if (!movil) {
            if (navOpened) {
                document.getElementById("map").style.setProperty('width', 'calc(100% - 400px - 380px)');
                $('#familias').css('width', 'calc(100dvw - 390px - 420px)');
                $('#familias').css('justify-content', 'center');
            } else {
                document.getElementById("map").style.setProperty('width', 'calc(100% - 380px)');
                $('#familias').css('width', 'calc(100dvw - 390px)');
                $('#familias').css('justify-content', 'center');
            }
            infoPanel.css('padding', '1.5em 3em 1em');
            infoPanel.css('width', '380px');
        } else {
            infoPanel.css('padding', '3em 3em 1em');
            infoPanel.css('width', '100dvw');
            infoPanel.css('height', '40dvh');
        }

        openInfo = true;

        // Mostrar un loader mientras carga
        infoPanel.html('<div class="d-flex justify-content-center align-items-center" style="height:200px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
    }

    // Pre-cargar los datos del popup
    function preloadPopupData(id_cliente, id_emp, emp, direccion, nombre, familia) {
        return new Promise((resolve) => {
            let logo = "";
            switch (parseInt(id_emp)) {
                case 1:
                    logo = `<svg height="60" viewBox="0 0 131 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_2410_4594)">
                            <path d="M43.4037 19.8514V18.5264H34.4365L28.9899 37.5648L23.5931 18.5264H18.047L26.4506 43.6075H31.3569L38.2422 23.2241V26.7608C39.1836 24.9372 41.176 21.4642 43.4004 19.8548L43.4037 19.8514Z" fill="#002C53"/>
                            <path d="M38.2455 36.1662V43.6075H43.4037V31.3514C42.26 32.1076 39.8566 33.8541 38.2455 36.1662Z" fill="#002C53"/>
                            <path d="M8.87102 18.5264H14.7452L23.5367 43.6075H17.9045L16.2635 38.4515H7.11405L5.43002 43.6075H0L8.87102 18.5264ZM8.52625 34.1285H14.8911L11.7518 24.2614L8.52625 34.1285Z" fill="#002C53"/>
                            <path d="M118.595 39.0972V18.5264H113.401V39.0972H100.661V32.6463H111.537V28.2932H100.661V22.9665H111.74V18.5264H95.5889V25.2216C95.2507 24.1744 94.7568 23.1873 94.1104 22.2538C93.0297 20.7113 91.6175 19.6507 89.8737 19.0718C88.8494 18.7305 87.5698 18.5499 86.025 18.5298H75.3141V43.6109H86.025C89.7842 43.6109 92.5589 42.0483 94.359 38.9333C94.8629 38.0499 95.2739 37.093 95.5855 36.0658V43.6142H131V39.1039H118.592L118.595 39.0972ZM90.2815 35.5907C89.3135 38.0299 87.603 39.2478 85.1498 39.2478H80.353V22.8828H85.1498C87.5101 22.8828 89.0914 23.5654 89.8837 24.9305C90.6793 26.299 91.0738 28.2564 91.0738 30.8093C91.0738 32.6563 90.8086 34.2523 90.2815 35.5907Z" fill="#002C53"/>
                            <path d="M75.8876 5.53419C75.8876 5.53419 72.3505 10.8341 65.6641 8.47192C61.5137 6.27364 65.0906 2.9344 65.8663 2.27191C66.8277 1.45216 70.9416 -1.15098 72.8246 0.588905C73.3284 1.05399 73.7362 1.65626 70.6897 4.29285C69.2377 5.58438 71.0444 7.32427 73.9749 5.7751C76.8987 4.23263 75.891 5.53754 75.891 5.53754L75.8876 5.53419Z" fill="#B1B3B4"/>
                            <path d="M80.3894 9.27828C80.3894 9.27828 73.6003 15.1838 79.3518 16.9672C81.3607 17.5862 83.2337 16.1475 83.5951 15.7493C84.9178 14.3005 83.9763 10.456 80.7342 12.8752C79.9287 13.4741 78.9673 12.9789 79.4977 12.1558C82.2658 7.86295 80.3894 9.27493 80.3894 9.27493V9.27828Z" fill="#B1B3B4"/>
                            <path d="M52.5962 43.9856C52.5962 43.9856 54.2703 45.2136 57.4693 43.4837C61.0032 41.5765 69.1382 35.5739 71.7836 26.9113C74.3594 18.4595 73.3881 13.5376 81.2215 6.96286C81.2215 6.96286 81.8481 6.31041 80.2204 6.13976C78.7352 6.09961 75.1948 6.6818 70.6333 11.9583C70.6333 11.9583 68.2995 14.779 67.2851 19.5369C66.3404 23.9669 66.1414 26.7808 64.4541 30.6521C59.0605 43.032 52.5929 43.9856 52.5929 43.9856H52.5962Z" fill="#B1B3B4"/>
                            <path d="M50.3984 15.7727C52.5797 14.6251 55.6096 13.9559 57.7246 14.4578C65.1171 16.2144 63.7911 24.2245 62.7038 28.4036C61.8617 31.6324 60.0352 35.3263 57.6019 37.9161C55.1753 40.5025 53.372 42.3863 48.9066 43.3934L48.595 43.1759C48.595 43.1759 53.1134 39.8902 54.8007 37.712C56.4848 35.5338 60.1943 29.1765 55.7886 25.8072C54.9532 25.1714 54.0648 24.8201 51.4691 25.4358C48.9663 26.0313 45.9927 27.5705 40.619 32.2113C38.8356 33.7471 36.3858 36.4372 35.5238 37.2134C35.5238 37.2134 40.3041 25.5161 51.0083 21.5378C51.0083 21.5378 47.0999 20.0789 39.0809 27.9519C39.0809 27.9519 40.745 20.8485 50.3951 15.7694L50.3984 15.7727Z" fill="#B1B3B4"/>
                            </g></svg>`;
                    break;
                case 2:
                    logo = `<svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="29.39 355.295 537.1 133.3"><!-- SVG case 2 --></svg>`;
                    break;
                case 3:
                    logo = `<svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="77.3697 355.96 441.2 130.9"><!-- SVG case 3 --></svg>`;
                    break;
                case 4:
                    logo = `<svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="211.236 57.37 1181 623.5"><!-- SVG case 4 --></svg>`;
                    break;
                default:
                    logo = `<svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="183.96 485.28 227.9 100.3"><!-- SVG default --></svg>`;
                    break;
            }

            let fam = familia.split(',');
            let familias = "";
            fam.forEach(element => {
                familias += '<div class="fam-pills selected d-flex gap-1 align-items-center" onclick="filterProducts(this)"><div class="fam_filter">' + element + '</div><span class="fam-pills-info selected">&check;</span></div>';
            });

            let content =
                '<div class="drag-icon"><span></span></div><div class="closePopUpButton" onclick="closePopupButton()"><span>X</span></div> <div class="popup-datos"><div>' +
                logo + '</div><h2 class="mt-5">' + nombre + '</h2><div class="d-flex gap-1 align-items-center"><div><span style="border:1px solid #003B71;padding:8px;border-radius:50%"><svg height="24" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path id="location_on" d="M6.00133 7.89552C6.37446 7.89552 6.69346 7.76265 6.95833 7.49689C7.22333 7.23114 7.35583 6.91171 7.35583 6.53858C7.35583 6.16546 7.22296 5.8464 6.95721 5.58139C6.69146 5.31652 6.37196 5.18408 5.99871 5.18408C5.62558 5.18408 5.30658 5.31696 5.04171 5.58271C4.77671 5.84846 4.64421 6.16796 4.64421 6.54121C4.64421 6.91433 4.77708 7.23333 5.04283 7.49821C5.30858 7.76308 5.62808 7.89552 6.00133 7.89552ZM6.00002 13.6317C7.46727 12.3182 8.59008 11.0583 9.36846 9.85208C10.1468 8.64583 10.536 7.58933 10.536 6.68258C10.536 5.31533 10.1016 4.19133 9.23289 3.31058C8.36414 2.42983 7.28652 1.98946 6.00002 1.98946C4.71352 1.98946 3.63589 2.42983 2.76714 3.31058C1.89839 4.19133 1.46402 5.31533 1.46402 6.68258C1.46402 7.58933 1.85321 8.64583 2.63158 9.85208C3.40996 11.0583 4.53277 12.3182 6.00002 13.6317ZM6.00002 15.1287C4.11252 13.4932 2.69714 11.9711 1.75389 10.5625C0.810645 9.15377 0.33902 7.86046 0.33902 6.68258C0.33902 4.95183 0.898832 3.55064 2.01846 2.47902C3.13821 1.40739 4.46539 0.871582 6.00002 0.871582C7.53464 0.871582 8.86183 1.40739 9.98158 2.47902C11.1012 3.55064 11.661 4.95183 11.661 6.68258C11.661 7.86046 11.1894 9.15377 10.2461 10.5625C9.30289 11.9711 7.88752 13.4932 6.00002 15.1287Z" fill="#003B71"/></svg></span></div><p class="p-0 m-0" style="font-size: 1rem;line-height: 150%;">' + direccion +
                '</p></div><div class="popup-prods"><h3 class="mt-4 pb-1 mb-3" style="color:#003B71; border-bottom:1px solid">Productos</h3><div id="fam_'+id_cliente+'" style="display:flex !important" class="d-flex gap-1 flex-wrap">'+familias+'</div><ul id="ul_' +
                id_cliente + '" class="mt-4" style="max-height:310px;overflow-y:auto"></ul></div>';

            // Cargar productos con AJAX
            if (xhr != null) {
                xhr.abort();
            }

            xhr = $.ajax({
                url: "{{ route('getClientProdsLocation') }}",
                type: 'GET',
                dataType: "json",
                data: {
                    id_empresa: id_emp,
                    id_cliente: id_cliente
                },
                success: function(response) {
                    let prods = "";
                    if (response.length == 0) {
                        prods = '<li>No hay productos</li>';
                    } else {
                        response.forEach(element => {
                            prods += `<li class="prod_fam prod_fam_${element.familia}">${element.name_cons}</li>`;
                        });
                    }

                    // Crear el contenido final con los productos
                    const finalContent = {
                        html: content,
                        products: prods,
                        id_cliente: id_cliente
                    };

                    resolve(finalContent);
                },
                error: function() {
                    resolve({
                        html: content,
                        products: '<li>Error al cargar productos</li>',
                        id_cliente: id_cliente
                    });
                }
            });
        });
    }

    // Mostrar el popup ya cargado
    function displayPopup(popupContent) {
        $('#info-panel').html(popupContent.html);
        $('#ul_' + popupContent.id_cliente).html(popupContent.products);
    }

</script>
@endpush
@endsection
