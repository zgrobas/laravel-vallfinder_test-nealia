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
            <div id="message"><span class="d-block d-md-none message-slogan">Nuestros productos, siempre cerca de ti.</span></div>
            <div id="familias">
                @foreach ($familias as $familia)
                    <span class="familia">{{ $familia->familia }}</span>
                @endforeach
            </div>
            <div id="map"></div>
            <button class="openbtn position-absolute" onclick="openNav()">
                <svg viewBox="-10 -10 120 120" width="16">
                    <path d="M 50,0 L 60,10 L 20,50 L 60,90 L 50,100 L 0,50 Z" transform="translate(85,100) rotate(180)" fill="currentColor" stroke="currentColor" stroke-width="10"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
<div id="mySidepanel" class="sidepanel">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
        <svg viewBox="-10 -10 120 120" width="16">
            <path d="M 50,0 L 60,10 L 20,50 L 60,90 L 50,100 L 0,50 Z" fill="currentColor" transform="translate(30,0)" stroke="currentColor" stroke-width="10"></path>
        </svg>
    </a>
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
                            <th width="100%">ESTABLECIMIENTOS</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
</div>

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"
    integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTluNOEfb1ZRy6D3BOw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/sl-2.0.3/datatables.min.css"
    rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
    integrity="sha512-kq3FES+RuuGoBW3a9R2ELYKRywUEQv0wvPTItv3DSGqjpbNtGWVdvT8qwdKkqvPzT93jp8tSF4+oN4IeTEIlQA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.min.css"
    integrity="sha512-ENrTWqddXrLJsQS2A86QmvA17PkJ0GVm1bqj5aTgpeMAfDKN2+SIOLpKG8R/6KkimnhTb+VW5qqUHB/r1zaRgg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.min.css"
    integrity="sha512-fYyZwU1wU0QWB4Yutd/Pvhy5J1oWAwFXun1pt+Bps04WSe4Aq6tyHlT4+MHSJhD8JlLfgLuC4CbCnX5KHSjyCg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    @font-face {
    font-family: 'Noteworthy Light';
    font-style: normal;
    font-weight: normal;
    src: url("{{ asset('json/Noteworthy-Lt.woff') }}") format('woff');
    }
    .message-slogan{
        font-family:'Noteworthy Light';
        font-weight:normal;
    }
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
    body{
        overscroll-behavior-y: none;
    }
    #message {
        color: white;
        background-color: #1c4077;
        text-align: center;
        height: 25px;
    }
    #message>span{
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
    #loading-page>div{
        position: absolute;
        top: 50%;
        left: 50%;
        height: 5em;
        width: 5em;
    }
    .dt-search {
        display: flex;
        gap: .5rem;
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
        position: relative;
        top: auto;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        transform: unset !important;
        margin: 0;
        border-radius: 0;
        max-height: calc(100dvh - 125px);
        overflow-y: auto;
        height: 40dvh;
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

    .leaflet-container a.leaflet-popup-close-button:visited, .leaflet-container a.leaflet-popup-close-button:focus,.leaflet-container a.leaflet-popup-close-button:active, .leaflet-container a.leaflet-popup-close-button:hover {
        color: white;
    }

    @keyframes border-pulse {
        0% {
            box-shadow: 0 0 0 0 rgb(232, 180, 97);
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
        background-color: #F7F7F7;
        overflow-x: hidden;
        transition: 0.5s;
    }

    .sidepanel .closebtn {
        position: absolute;
        text-decoration: none;
        right: 0;
        border-radius: 10px 0 0 10px;
        border: 1px solid #5e5e5e;
    }

    .sideWrapper {
        padding: 0 2em ;
    }

    .sidepanel .closebtn,
    .openbtn {
        font-size: 20px;
        cursor: pointer;
        background-color: #F7F7F7;
        color: #111;
        padding: 5px;
        top: 50%;
        transform: translateY(-50%);
        transition: .3s;
    }

    .openbtn {
        z-index: 999;
        left: 0;
        border-radius: 0 10px 10px 0;
        border: 1px solid #F7F7F7;
        background-color: orange;
        color: #F7F7F7;
    }

    .openbtn:hover,
    .closebtn:hover {
        /* background-color: #5e5e5e; */
        background-color: orange;
        border: 1px solid #5e5e5e;
        color: #f7f7f7;
    }

    .tab-content {
        min-height: 200px
        /* min-height: 80px */
    }
    div.dt-container div.dt-search label{
        display: none !important;
    }
    .dt-search>input, div.dtsp-panesContainer div.dtsp-searchPane div.dtsp-topRow div.dtsp-subRow1 input {
        background: #FFFFFF 0% 0% no-repeat padding-box;
        box-shadow: inset 0px 3px 6px #00000029;
        border-radius: 10px;
        height: 45px;
        line-height: 1rem;
        font-size: 1rem;
        margin: 0 !important;
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
    }
    div.dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane div.dt-container,
    div.dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane div.dataTables_wrapper,
    div.dtsp-panesContainer div.dtsp-searchPane div.dt-container:hover,
    div.dtsp-panesContainer div.dtsp-searchPane div.dataTables_wrapper:hover{
        border: 0 !important;
    }

    .popup-datos{
        text-align: center;
    }
    .select-info{
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
            width: 30dvw;
            height: 100% !important;
            right: 0 !important;
            left: unset !important;
            position: relative;
            min-height: 800px;
            max-height: 100dvh;
        }

        .leaflet-popup-content {
            height: 100%;
            width: 100% !important;
        }

        .drag-icon { display: none}
    }

    .dtsp-panesContainer div.dtsp-searchPanes div.dtsp-searchPane, .dtsp-topRow.dtsp-subRowsContainer.dtsp-bordered{
        position: relative;
    }
    .dtsp-subRow2{
        position: absolute;
        right: 0;
    }
    .dtsp-searchPane div.dt-container{
        position: absolute;
        z-index: 1;
    }
    .dtsp-searchIcon{
        display: none;
    }
    #ubc-filter{
        gap: .3rem;
    }
    div.dtsp-panesContainer div.dtsp-searchPane div.dtsp-topRow button{
        line-height: 35px;
    }

    div.dtsp-panesContainer div.dtsp-searchPane div.dtsp-topRow button.dtsp-rotated span{
        top: unset;
    }

    .dt-scroll-body{
        height: 230px;
    }

    .dtsp-searchPane .dt-scroll-body{
        height: 150px !important;
    }

    #familias {
        background: transparent;
        left: 0;
        position: absolute;
        top: calc(25px + .7rem);
        z-index: 1;
        display: flex;
        gap: .5rem;
        width: 100dvw;
        justify-content: center;
        padding: 0 4em;
        overflow-x: auto;
        transition: .3s;
    }
    #familias>span {
        border: 1px solid #1c4175;
        background-color: orange;
        color: white;
        border-radius: 15pc;
        padding: .2rem;
        cursor: pointer;
        min-width: 90px;
        text-align: center;
        margin-bottom: .2rem;
        transition: .3s;
    }
    #familias>span.selected{
        font-weight: bold;
        background-color: #1c4175;
        border-color: orange;
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
    .leaflet-popup-content p{
        margin: 2em 0 1em;
    }
    .separator-prods{
        box-shadow: 0px 3px 6px #00000029;
        border: 1px solid #FFFFFF;
    }

    td.min-mobile-p.dtr-control {
        font-weight: 600;
    }
    @media (max-width: 700px) {
        #familias{
            left: 3.3rem;
            width: calc(100dvw - 3.3rem);
            padding: 0;
            overflow-x: auto;
            justify-content: flex-start;
        }
        #familias>span{
            padding: .3rem;
        }
    }
</style>
@endpush
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet-src.min.js"
    integrity="sha512-3/WyQrhTdqSVmSifQS62akgtNBhZha2lS44TnoN9Jk3J01FvsKK4suVmz6t5FtccGb5iJw58GoFhBjPE5EPc8Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster-src.min.js"
    integrity="sha512-tWZsle/wNvFCP/7BHWNf3yG/6Ic1YVPT+1Soyag9zzZcga1St3oAa+IgvrT3ZxBLY/njmSEr0EcQoPUrH+UfNg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/sl-2.0.3/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let empresas = @json($companies);
    let categorias = @json($categories);
    let paises = @json($paises);
    let ciudades = @json($ciudades);
    let zipcodes = @json($zipcodes);
    let familias_selected = "";
    let cluster;
    let markers = [];
    const config = { minZoom: 5, maxZoom: 18 };
    const zoom = 15;
    let map;
    let noGPS = false;
    let table;
    let pane;
    let xhr = null;
    let movil = false;

    // Global variables for tracking drag events
    let isDragging = false, startY, startHeight;
    let sheetContent = null;
    let dragIcon = null;
    let bottomSheet;
    $(document).ready(function(){
        map = L.map("map", config);
        L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.{ext}', {
            minZoom: 5,
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a>',
            ext: 'png'
        }).addTo(map);
        map.locate({
            setView: true,
            enableHighAccuracy: true,
        })
        .on("locationfound", (e) => {
            // useMapFilter = true;
            const svgTemplate = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 53.545 53.545" class="marker">
                    <g>
                        <circle style="fill:#FFA100;" cx="26.686" cy="4.507" r="4.507"/>
                        <path style="fill:#FFA100;" d="M28.256,11.163c-1.123-0.228-2.344-0.218-3.447,0.042c-7.493,0.878-9.926,9.551-9.239,16.164
                            c0.298,2.859,4.805,2.889,4.504,0c-0.25-2.41-0.143-6.047,1.138-8.632c0,3.142,0,6.284,0,9.425c0,0.111,0.011,0.215,0.016,0.322
                            c-0.003,0.051-0.015,0.094-0.015,0.146c0,7.479-0.013,14.955-0.322,22.428c-0.137,3.322,5.014,3.309,5.15,0
                            c0.242-5.857,0.303-11.717,0.317-17.578c0.244,0.016,0.488,0.016,0.732,0.002c0.015,5.861,0.074,11.721,0.314,17.576
                            c0.137,3.309,5.288,3.322,5.15,0c-0.309-7.473-0.32-14.949-0.32-22.428c0-0.232-0.031-0.443-0.078-0.646
                            c-0.007-3.247-0.131-6.497-0.093-9.742c1.534,2.597,1.674,6.558,1.408,9.125c-0.302,2.887,4.206,2.858,4.504,0
                            C38.678,20.617,36.128,11.719,28.256,11.163z"/>
                    </g>
                    </svg>`;

            const icon = L.divIcon({
                className: "marker",
                html: svgTemplate,
                iconSize: [25, 40],
                iconAnchor: [12, 24],
                popupAnchor: [7, -16],
            });
            const marker = L.marker([e.latitude, e.longitude], { icon: icon }).bindPopup("Posición actual");
            const circle = L.circle([e.latitude, e.longitude], e.accuracy / 2, {
                weight: 2,
                color: "#FFA100",
                fillColor: "#FFA100",
                fillOpacity: 0.2,
            });
            map.addLayer(marker);
            map.addLayer(circle);
            // setBounds();
        })
        .on("locationerror", (e) => {
            map.setView([41.6238341,0.6591355,16], zoom);
            alert('Acceso a la geolocalización denegada');
        })
        .on("popupclose", function (e) {
            removeAllAnimationClassFromMap();
        });
        pane = map.createPane("fixed", document.getElementById("map"));
        cluster = L.markerClusterGroup();
        table = new DataTable('#clients2', configDes);
        table.on('search.dt', function () {
            let companiesF = [];
            let categoriesF = [];
            let paisesF = [];
            let ciudadesF = [];
            let zipcodesF = [];
            $('#DataTables_Table_3 tbody tr.selected').each(function (i){
                empresas.forEach(e => {
                    if (e.name === $(this).find('span:first-child').text())
                    companiesF.push(e.id);
                });
            });
            $('#DataTables_Table_4 tbody tr.selected').each(function (i){
                categoriesF.push($(this).find('span:first-child').text());
            });
            $('#DataTables_Table_2 tbody tr.selected').each(function (i){
                zipcodesF.push($(this).find('span:first-child').text());
            })
            $('#DataTables_Table_1 tbody tr.selected').each(function (i){
                ciudadesF.push($(this).find('span:first-child').text());
            })
            $('#DataTables_Table_0 tbody tr.selected').each(function (i){
                paisesF.push($(this).find('span:first-child').text());
            })

            if(zipcodesF.length > 0){
                cluster.eachLayer(function (layer){
                    if(layer.options.zip != undefined){
                        (zipcodesF.find(( el ) => el == layer.options.zip)) ? cluster.addLayer(layer) : cluster.removeLayer(layer);
                    }
                })
            }
            if(ciudadesF.length > 0){
                cluster.eachLayer(function (layer){
                    if(layer.options.ciu != undefined){
                        (ciudadesF.find(( el ) => el == layer.options.ciu)) ? cluster.addLayer(layer) : cluster.removeLayer(layer);
                    }
                })
            }
            if(paisesF.length > 0){
                cluster.eachLayer(function (layer){
                    if(layer.options.pai != undefined){
                        (paisesF.find(( el ) => el == layer.options.pai)) ? cluster.addLayer(layer) : cluster.removeLayer(layer);
                    }
                })
            }
            if(categoriesF.length > 0){
                cluster.eachLayer(function (layer){
                    if(layer.options.cat != undefined){
                        (categoriesF.find(( el ) => el == layer.options.cat)) ? cluster.addLayer(layer) : cluster.removeLayer(layer);
                    }
                })
            }
            if(companiesF.length > 0){
                cluster.eachLayer(function (layer){
                    if(layer.options.emp != undefined){
                        (companiesF.find(( el ) => el == layer.options.emp)) ? cluster.addLayer(layer) : cluster.removeLayer(layer);
                    }
                })
            }
            if(companiesF.length == 0 && categoriesF.length == 0 && paisesF.length == 0 && ciudadesF.length == 0 && zipcodesF.length == 0){
                //elminamos todos los marcadores para evitar duplicarlos
                // cluster.eachLayer(function (layer){
                //     if(layer.options.emp != undefined){
                //         cluster.removeLayer(layer);
                //     }
                // })
                cluster.clearLayers();
                markers.forEach(m =>{
                    m.addTo(cluster);
                })
            }
        })
        .on('user-select', function (e, dt, type, cell, originalEvent) {
            e.preventDefault();
        });
        paintTable();
        bottomSheet = document.querySelector(".leaflet-pane.leaflet-fixed-pane");

        // $('#pills-tab a').on('click', function (e) {
        //     e.preventDefault()
        //     $(this).tab('show')
        // })
        $(document).ajaxComplete(function(event, xhr, settings){
            $("#emp-filter").prepend($(".sp-original-emp"));
            $("#ubc-filter").prepend($(".sp-original-ubc"));
            $('#clients2_wrapper>div').addClass('mt-5');
            $('#clients2_wrapper>div').eq(-2).hide();
            $('#clients2_wrapper>div').last().removeClass('mt-5');
            $('.dtsp-caret').text('');
            $('.dtsp-caret').html('<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"><path fill="#1c4077" d="M23.677 18.52c.914 1.523-.183 3.472-1.967 3.472h-19.414c-1.784 0-2.881-1.949-1.967-3.472l9.709-16.18c.891-1.483 3.041-1.48 3.93 0l9.709 16.18z"/></svg>');
            $(".dt-search>input").attr('placeholder', 'Buscar establecimiento...')
            $('.dtsp-titleRow').first().parent().parent().parent().hide();
            if ( $('#emp-filter').children().length > 0 ) {
                $('#loading-page').hide();
                // if(sheetContent === null){
                    sheetContent = bottomSheet.querySelector(".popup-fixed");
                    dragIcon = bottomSheet.querySelector(".drag-icon");
                    if(dragIcon !== null){
                        dragIcon.addEventListener("touchstart", dragStart);
                        document.addEventListener("touchmove", dragging);
                        document.addEventListener("touchend", dragStop);
                    }
                // }
            }
        });
        $('.familia').click(function (){
            $(this).hasClass('selected') ? $(this).removeClass('selected') : $(this).addClass('selected');

            let familia_sel = "";
            $('.familia.selected').each(function(i, obj) {
                familia_sel += $(this).text() + ",";
            });
            (familia_sel.length > 0) ? paintTable("familias="+familia_sel) : paintTable();
        });
    });


    function tabAction(e){
        e.preventDefault()
        let a = e.target.id;
        a = a.substring(0, a.length - 4);
        $('#pills-tab a[href="#'+a+'"]').tab('show')
    }

    const configDes = {
        // ordering: false,
        searching: true,
        responsive: true,
        processing: true,
        searchPane: true,
        pagingType: 'full_numbers',
        columns: [
            {
                data: "id_empresa",
                className: "min-mobile-p",
                visible:false
            },
            {
                data: 'Empresa',
                visible:false,
                searchPanes: {
                    header: 'Empresa',
                    show: true,
                    // initCollapsed: true,
                    className: 'sp-original-emp',
                    orderable: false,
                    collapse: false,
                    clear:false,
                },
            },
            {
                data: "nombre",
                className: "min-mobile-p",
                orderable: false, visible:false
            },
            { data: "direccion", className: "min-mobile-p", orderable: false, visible:false },
            {
                data: "tipocliente",
                className: "min-mobile-p",
                visible:false,
                searchPanes: {
                    show: true,
                    combiner: 'or',
                    initCollapsed: true,
                    className: 'sp-original-ubc',
                    orderable: false,
                    clear:false,
                },
            },
            {
                data: "zipcode",
                visible:false,
                className: "min-mobile-p",
                searchPanes: {
                    header: 'Cód. Postal',
                    show:true,
                    combiner: 'or',
                    initCollapsed: true,
                    className: 'sp-original-ubc',
                    orderable: false,
                    clear:false,
                }
            },
            {
                data: "ciudad",
                visible:false,
                className: "min-mobile-p",
                searchPanes: {
                    header: 'Ciudad',
                    show:true,
                    combiner: 'or',
                    initCollapsed: true,
                    className: 'sp-original-ubc',
                    orderable: false,
                    clear:false,
                }
            },
            {
                data: "id_pais",
                visible:false,
                className: "min-mobile-p",
                searchPanes: {
                    header: 'País',
                    show:true,
                    combiner: 'or',
                    initCollapsed: true,
                    className: 'sp-original-ubc',
                    orderable: false,
                    clear:false,
                }
            },
            { data: "accion", className: "min-mobile-p", orderable: true },
        ],
        layout:{
            top1:{
                searchPanes: {
                    className:'def-filters',
                    dtOpts: {
                        select: {
                            style: 'multi'
                        }
                    },
                    viewTotal: true,
                    columns: [ 7, 6, 5, 1, 4],
                    clear:false,
                }
            },
            top3:'search',
            top2: function(){
                let toolbar = document.createElement('div');
                toolbar.innerHTML = '<ul class="nav nav-tabs  nav-justified mb-3" id="pills-tab" role="tablist"><li class="nav-item"><a onclick="tabAction(event)" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Empresas</a></li><li class="nav-item"><a onclick="tabAction(event)" class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Ubicación</a></li></ul><div class="tab-content" id="pills-tabContent"><div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"><div class="dtsp-panes dtsp-panesContainer"><div id="emp-filter" class="dtsp-searchPanes"></div></div></div><div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"><div class="dtsp-panes dtsp-panesContainer"><div id="ubc-filter" class="dtsp-searchPanes"></div></div></div></div>';
                return toolbar;
            },
            topStart:null,
            topEnd:null,
            bottomStart:'pageLength',
            bottomEnd:null,
            bottom2Start: 'info',
            bottom2End: 'paging'
        },
        select: {
            style: 'os',
            selector: 'td:first-child'
        },
        order: [[2, 'asc']],
        language: {
            url: "{{ asset('json/DataTable-ES.json') }}?v1.0"
        },
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, 'Todos']
        ],
        paging: false,
        scrollCollapse: true,
        scrollY: 270
    };


    const paintTable = async(data="") =>{
        try {
            $.ajax({
                url: "{{ route('getClients') }}",
                type: 'GET',
                dataType: "json",
                data: data,
                beforeSend: function(){
                    $('.dt-empty').attr('colspan', '9');
                    $('#clients2_processing').show();
                    markers = [];
                    table.clear().draw();
                },
                success: function(response) {
                    index = 0;
                    response.forEach(element => {
                        //agregamos los marcadores al mapa
                        let color = "red";
                        let rotate = 234.07;
                        index = 0;
                        //verificamos si el marcador existe en el mapa, para no volver a colocarlo
                        if (!markers.some(marker => (marker.options.ic === element.id_cliente && marker.options.emp === element.id_empresa))) {
                            let el = L.marker([element.latitud, element.longitud], {icon: colorMarker(element.id_cliente, color, rotate), ic: element.id_cliente, emp: element.id_empresa, cat: element.tipocliente, pai: element.id_pais, ciu: element.ciudad, zip: element.zipcode}).bindPopup($("#pcc")[0], {}); //.addTo(map);
                            options = { id: element.id_cliente, id_empresa : element.id_empresa, nombre : element.nombre, dir : element.direccion, emp : element.Empresa };
                            // crewate popup, set contnet
                            const popup = L.popup({
                                pane: "fixed",
                                className: "popup-fixed",
                                autoPan: false,
                                maxWidth:700,
                                closeButton: false,
                                closeOnClick: false
                            }).setContent('<div class="popup-content-wrapper" id="'+element.id_cliente+'"></div>');
                            el.bindPopup(popup);
                            el.on("click", function(e) {
                                // Prevenir que el popup se abra automáticamente
                                e.target.closePopup();
                                // Llamar a la función de animación con el contexto correcto
                                fitBoundsPadding.call(options, e);
                            });
                            cluster.addLayer(el);
                            markers.push(el);
                            // estructura[element.id_empresa][1][element.tipocliente].addLayer(el);
                            //agregamos los elementos a la tabla

                            element.accion = '<div class="w-100" style="cursor:pointer" data-info="'+el._leaflet_id+'" data-id="'+element.id_cliente+'" data-id_empresa="'+element.id_empresa+'" data-emp="'+element.Empresa+'" data-dir="'+element.direccion+'" data-nombre="'+element.nombre+'" onclick="ubicarCliente(this)" title="Ubicar en el mapa">'+element.nombre+'</div>'
                            // element.accion = '<div class="btn btn-outline-secondary" data-info="'+el._leaflet_id+'" data-id="'+element.id_cliente+'" data-id_empresa="'+element.id_empresa+'" data-emp="'+element.Empresa+'" data-dir="'+element.direccion+'" data-nombre="'+element.nombre+'" onclick="ubicarCliente(this)" title="Dirigir al mapa"><svg xmlns="http://www.w3.org/2000/svg" width="30" viewBox="0 0 576 512"><path d="M288 0c-69.6 0-126 56.4-126 126 0 56.3 82.4 158.8 113.9 196 6.4 7.5 17.8 7.5 24.2 0C331.7 284.8 414 182.3 414 126 414 56.4 357.6 0 288 0zm0 168c-23.2 0-42-18.8-42-42s18.8-42 42-42 42 18.8 42 42-18.8 42-42 42zM20.1 216A32 32 0 0 0 0 245.7v250.3c0 11.3 11.4 19.1 21.9 14.9L160 448V214.9c-8.8-16-16.1-31.5-21.3-46.4L20.1 216zM288 359.7c-14.1 0-27.4-6.2-36.5-17-19.7-23.2-40.6-49.6-59.5-76.7v182l192 64V266c-18.9 27.1-39.8 53.5-59.5 76.7-9.1 10.8-22.4 17-36.5 17zm266.1-198.5L416 224v288l139.9-56A32 32 0 0 0 576 426.3V176c0-11.3-11.4-19.1-21.9-14.9z"/></svg></div>';
                            table.row.add(element);
                            index++;
                        }
                    });
                },
                statusCode: {
                    403: function(){
                        alert("Intenta iniciar sesión nuevamente")
                    }
                },
                complete:function(xhr, status){
                    table.draw();
                    table.searchPanes.rebuildPane();
                    map.addLayer(cluster);
                    $('#clients2_processing').hide();
                    if(!movil){
                        openNav();
                    }
                },

            });
        } catch (error) {
            alert('Ha habido un error al momento de cargar los datos de la tabla')
        }
    }
    function colorMarker(id, classes, rotate) {
        // <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="marker">
        //         <linearGradient id="gradient${id}" gradientTransform="rotate(${rotate})" class="mapMarker ${classes}">
        //             <stop class="main-stop" offset="0%"></stop>
        //             <stop class="alt-stop" offset="100%"></stop>
        //         </linearGradient>
        //         <path fill-opacity=".25" d="M16 32s1.427-9.585 3.761-12.025c4.595-4.805 8.685-.99 8.685-.99s4.044 3.964-.526 8.743C25.514 30.245 16 32 16 32z"/>
        //         <path class="mark_st" fill="#003B71" d="M15.938 32S6 17.938 6 11.938C6 .125 15.938 0 15.938 0S26 .125 26 11.875C26 18.062 15.938 32 15.938 32zM16 6a4 4 0 100 8 4 4 0 000-8z"/>
        //         <path fill="#003B71" d="M 17.3683 7.5294 c 0 0 -0.2661 -0.0015 -0.4271 0.1595 c -0.161 0.161 -2.2914 2.2907 -2.2914 2.2907 v -1.4984 c 0 -1.0426 -1.3759 -0.9518 -1.3759 -0.9526 v 3.8269 v 0.9488 l 1.3759 -1.3775 l 3.3982 -3.3982 C 18.0479 7.5294 17.3683 7.5294 17.3683 7.5294 z "/>
        //         <path fill="#003B71" d="M 16.9418 10.9253 h -0.9722 c 0 0 1.9369 -1.9376 2.0986 -2.0986 c 0.161 -0.161 0.1595 -0.4271 0.1595 -0.4271 v -0.6797 c 0 0 -3.0558 3.055 -3.2848 3.2848 c -0.4438 0.443 -0.1913 1.2973 0.5323 1.2973 h 2.9453 v -0.0007 C 18.2414 11.7721 17.7523 10.9291 16.9418 10.9253 z "/>
        //         <path fill="#888B8D" d="M 18.4016 10.6645 h -1.2459 c 0.9103 0.2056 1.3472 0.8059 1.5732 1.6836 v -1.3419 C 18.7282 11.0062 18.7464 10.6645 18.4016 10.6645 z"/>
        //     </svg>
        const svgTemplate = `

            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 75.6 100" class="marker">
                <g id="gradient${id}" gradientTransform="rotate(${rotate})" class="mapMarker ${classes}">
                    <path fill="#123f75" stroke-width="0" d="M67,14.2C61,6.5,52.2,1.6,42.6.4c-1.6-.2-3.2-.3-4.8-.4h0c-1.6,0-3.2.1-4.8.4C23.4,1.6,14.6,6.5,8.6,14.2,2.1,22-.9,32,.2,42.1c.5,6.4,2.8,12.6,6.5,17.9,9.7,13.2,19.6,26.3,29.4,39.5.2.2.3.5.5.5h2.4c.2,0,.3-.3.5-.5,9.8-13.2,19.7-26.3,29.4-39.5,3.7-5.3,6-11.5,6.5-17.9,1.1-10.1-1.9-20.1-8.4-27.9Z"/>
                    <path fill="#fff" stroke-width="0" d="M47.6,20.6c-1.1,0-2.1.4-2.9,1.1l-15.5,15.5v-10.1c0-7-9.3-6.4-9.3-6.4v32.2l9.3-9.3,22.9-23h-4.5Z"/>
                    <path fill="#fff" stroke-width="0" d="M44.7,43.5h-6.6s13.1-13.1,14.2-14.2c.7-.8,1.1-1.8,1.1-2.9v-4.6s-20.6,20.6-22.2,22.2c-3,3-1.3,8.8,3.6,8.8h19.9c-1.2-3.6-4.5-9.3-10-9.3"/>
                    <path fill="#fff" stroke-width="0" d="M54.5,41.7h-8.4c6.1,1.4,9.1,5.4,10.6,11.4v-9.1c0-1.2-.8-2.2-2-2.3h-.3"/>
                </g>
            </svg>`;

        const icon = L.divIcon({
            className: "marker",
            html: svgTemplate,
            iconSize: [40, 40],
            iconAnchor: [12, 24],
            popupAnchor: [7, -16],
        });

        return icon;
    }
    async function ubicarCliente(el){
        let id_cliente = $(el).data('id');
        let id_emp = $(el).data('id_empresa');
        let emp = $(el).data('emp');
        let direccion = $(el).data('dir');
        let nombre = $(el).data('nombre');
        mark = markers.find((m => m.options.ic == id_cliente ));
        let id = mark._leaflet_id;

        console.log('ubicarCliente llamado para cliente:', id_cliente);

        $('html, body').animate({
            scrollTop: $("#map").offset().top
        }, 300);

        cluster.eachLayer(function (layer){
            if(layer.options && layer._leaflet_id === id){
                centerMarker(layer, id_cliente, id_emp, emp, direccion, nombre);
            }
        })
    }


    function updateSheetHeight(height){
        sheetContent.style.height = `${height}dvh`;
    }

    // Sets initial drag position, sheetContent height and add dragging class to the bottom sheet
    const dragStart = (e) => {
        isDragging = true;
        startY =  e.touches?.[0].pageY;
        startHeight = parseInt(sheetContent.style.height);

        bottomSheet.classList.add("dragging");
    }

    // Calculates the new height for the sheet content and call the updateSheetHeight function
    const dragging = (e) => {
        if(!isDragging) return;
        const delta = startY - (e.touches?.[0].pageY);
        const newHeight = startHeight + delta / window.innerHeight * 100;
        updateSheetHeight(newHeight);
    }

    // Determines whether to hide, set to fullscreen, or set to default
    // height based on the current height of the sheet content
    const dragStop = () => {

        if(!isDragging) return;
        else{
            isDragging = false;
            bottomSheet.classList.remove("dragging");
            const sheetHeight = parseInt(sheetContent.style.height);
            if(sheetHeight < 25){
                map.closePopup();
                $('#familias').css('z-index','1');
            }else if(sheetHeight > 60){
                updateSheetHeight(100);
                $('#familias').css('z-index','-1');
            }else{
                updateSheetHeight(40);
                $('#familias').css('z-index','1');
            }

            // sheetHeight < 25 ? map.closePopup() : sheetHeight > 60 ? updateSheetHeight(100) : updateSheetHeight(40);
        }

    }


    // function open popup and centering
    // the map on the marker
    async function centerMarker(layer, id_cliente, id_emp, emp, direccion, nombre) {
        console.log('centerMarker llamado');
        // Usar la animación en lugar de zoomToShowLayer directo
        await animateMarkerTransition(layer, id_cliente, id_emp, emp, direccion, nombre);
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

    function loadPopUp(id_cliente, id_emp, emp, direccion, nombre){
        closeNav();
        if(!movil){
            $('#familias').css('width', 'calc(100dvw - 30dvw)');
            // $('#familias').css('padding', '0');
            // $('#familias').css('justify-content', 'flex-start');
        }
        if(sheetContent !== null)
            updateSheetHeight(40);
        var logo="";
        switch (parseInt(id_emp)) {
            case 1:
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="80" viewBox="51.98 313.66 491.8 219.7">
                            <path fill="#00263A" d="M84.54,380.95h21.57l32.27,91.23h-20.67l-6.02-18.75H78.1l-6.18,18.75H51.98L84.54,380.95z M83.29,437.7     h23.36l-11.52-35.9L83.29,437.7z"/>
                            <path fill="#00263A" d="M192.38,445.6v26.57h18.95v-43.81C207.36,430.93,198.47,437.21,192.38,445.6"/>
                            <path fill="#00263A" d="M211.33,386.52v-5.57H178.4l-19.99,69.25l-19.81-69.25h-20.36l30.85,91.23h18.01l25.28-74.14v13.19     C196.02,404.35,203.28,392.08,211.33,386.52"/>
                            <path fill="#00263A" d="M498.3,455.77v-74.82h-19.06v71.51l-25.3-71.51h-21.57l-25.71,72.02v-29.75h-38v15.22h21.1     c-0.95,5.95-3.47,10.75-7.56,14.42c-4.08,3.67-9.32,5.5-15.71,5.5c-6.53,0-12.34-2.33-17.45-6.98     c-5.12-4.65-7.68-12.58-7.68-23.78c0-11.12,2.37-19.45,7.08-24.98c4.73-5.54,10.76-8.31,18.11-8.31c3.83,0,7.32,0.68,10.46,2.04     c5.61,2.48,9.13,6.83,10.58,13.06h18.75c-1.03-8.79-5.06-16.12-12.1-22c-7.03-5.88-16.35-8.82-27.94-8.82     c-13.37,0-24.12,4.45-32.24,13.36c-8.13,8.91-12.19,20.74-12.19,35.5c0,14.6,4.03,26.1,12.07,34.51     c7.71,8.37,17.62,12.56,29.7,12.56c7.35,0,13.53-1.54,18.56-4.62c2.93-1.77,6.24-4.81,9.9-9.12l1.92,11.39h25.71l6.18-18.75h33.6     l6.01,18.75h78.31v-16.4H498.3z M431.12,437.7l11.83-35.9l11.53,35.9H431.12z"/>
                            <path fill="#8C9091" d="M331.15,333.79c0,0-12.98,19.28-37.53,10.69c-15.24-8-2.11-20.15,0.74-22.56     c3.54-2.98,18.63-12.46,25.55-6.12c1.85,1.69,3.34,3.88-7.85,13.47c-5.34,4.7,1.3,11.03,12.05,5.4     C334.85,329.04,331.15,333.79,331.15,333.79"/>
                            <path fill="#8C9091" d="M347.68,347.4c0,0-24.92,21.48-3.8,27.98c7.36,2.26,14.24-2.98,15.58-4.42c4.85-5.27,1.4-19.25-10.51-10.46     c-2.96,2.18-6.48,0.38-4.54-2.61C354.56,342.26,347.68,347.4,347.68,347.4"/>
                            <path fill="#8C9091" d="M245.64,473.65c0,0,6.14,4.47,17.89-1.83c12.98-6.93,42.84-28.76,52.55-60.27     c9.47-30.74,5.89-48.64,34.65-72.56c0,0,2.29-2.38-3.67-3c-5.46-0.15-18.46,1.96-35.19,21.16c0,0-8.57,10.26-12.29,27.57     c-3.48,16.12-4.2,26.34-10.39,40.43C269.39,470.18,245.64,473.65,245.64,473.65"/>
                            <path fill="#8C9091" d="M237.57,371.03c8.01-4.17,19.13-6.61,26.89-4.78c27.14,6.38,22.27,35.52,18.27,50.73     c-3.09,11.75-9.79,25.19-18.71,34.61c-8.91,9.41-15.53,16.26-31.92,19.93l-1.14-0.8c0,0,16.59-11.95,22.77-19.87     c6.19-7.93,19.81-31.05,3.64-43.32c-3.06-2.31-6.33-3.59-15.87-1.35c-9.18,2.17-20.1,7.77-39.83,24.65     c-6.54,5.59-15.54,15.38-18.71,18.2c0,0,17.56-42.55,56.86-57.02c0,0-14.36-5.31-43.79,23.33     C196.03,415.34,202.14,389.51,237.57,371.03"/>
                            <path fill="#8C9091" d="M540.73,532.02v-9.38h-2.87v9.38H540.73z M534.98,531.51v-8.36c0-1.01,1.95-1.84,4.32-1.84     c2.37,0,4.31,0.83,4.31,1.84v8.36c0,1.01-1.94,1.84-4.31,1.84C536.92,533.35,534.98,532.52,534.98,531.51z M369.93,531.51v-8.36     c0-1.01,1.94-1.84,4.32-1.84c2.37,0,4.32,0.83,4.32,1.84v1.34h-2.88v-1.86h-2.88v9.38h2.88v-4.08h-1.24v-1.22h4.12v4.79     c0,1.01-1.94,1.84-4.32,1.84C371.87,533.35,369.93,532.52,369.93,531.51z M411.37,533.25v-11.83h5.76c1.59,0,2.87,0.55,2.87,1.23     v3.75c0,0.56-0.58,0.78-1.28,0.94c0.7,0.12,1.28,0.37,1.28,1.09v3.95c0,0.33,0.31,0.58,0.7,0.88h-2.95     c-0.31-0.25-0.62-0.58-0.62-0.88v-4.43h-2.88v5.3H411.37z M414.24,522.64v4.08h2.88v-4.08H414.24z M461.43,521.42v10.1     c0,1.01-1.94,1.84-4.31,1.84c-2.37,0-4.31-0.83-4.31-1.84v-10.1h2.88v10.61h2.88v-10.61H461.43z M494.25,533.25v-11.83H500     c1.59,0,2.88,0.55,2.88,1.23v4.08c0,0.68-1.29,1.22-2.88,1.22h-2.88v5.3H494.25z M497.12,522.64v4.08H500v-4.08H497.12z"/>
                            <path fill="#00263A" d="M539.65,493.42v-3.8h-3.9v8.99h3.9c2.16,0,3.9,1.69,3.9,3.8v7.39c0,3.13-2.64,5.7-5.85,5.7     c-3.22,0-5.85-2.57-5.85-5.7v-2.21h3.9v3.8h3.9v-8.99h-3.9c-2.16,0-3.9-1.69-3.9-3.8v-7.4c0-3.13,2.64-5.7,5.85-5.7     c3.22,0,5.85,2.57,5.85,5.7v2.21H539.65z M371.85,515.19l-4.54-36.67h3.95l1.74,18.03c0.21,2.31,0.47,4.57,0.63,9.24     c0.26-4.67,0.53-6.93,0.74-9.24l1.74-18.03h3.96l-4.53,36.67H371.85z M394.19,489.62v25.57h-7.81c-2.16,0-3.9-1.69-3.9-3.8     v-14.02c0-2.11,1.74-3.8,3.9-3.8h3.9v-3.96h-6.96v-3.8h6.96C392.45,485.82,394.19,487.51,394.19,489.62z M386.39,497.37v14.02     h3.9v-14.02H386.39z M397.63,515.19v-36.97c1.05,0.05,2,0.46,2.74,1.13c0.74,0.67,1.16,1.64,1.16,2.67v33.17H397.63z      M404.91,515.19v-36.97c1.05,0.05,2,0.46,2.74,1.13c0.74,0.67,1.16,1.64,1.16,2.67v33.17H404.91z M419.52,509.8v-25.88     c0-3.13,2.64-5.7,5.85-5.7c3.22,0,5.85,2.57,5.85,5.7v4.16h-3.9v-5.75h-3.9v29.07h3.9v-5.75h3.9v4.16c0,3.13-2.64,5.7-5.85,5.7     C422.16,515.5,419.52,512.93,419.52,509.8z M433.69,509.8v-18.59c0-3.13,2.64-5.7,5.86-5.7c3.21,0,5.85,2.57,5.85,5.7v18.59     c0,3.13-2.64,5.7-5.85,5.7C436.33,515.5,433.69,512.93,433.69,509.8z M437.59,489.62v21.78h3.9v-21.78H437.59z M448.82,515.19     v-29.37h15.61c2.16,0,3.9,1.7,3.9,3.8v25.57h-3.9v-25.57h-3.9v25.57h-3.9v-25.57h-3.9v25.57H448.82z M471.74,522.38v-36.56h7.81     c2.16,0,3.9,1.7,3.9,3.8v21.78c0,2.1-1.74,3.8-3.9,3.8h-3.9v7.19H471.74z M475.65,489.62v21.78h3.9v-21.78H475.65z      M498.58,489.62v25.57h-7.81c-2.16,0-3.9-1.69-3.9-3.8v-14.02c0-2.11,1.74-3.8,3.9-3.8h3.9v-3.96h-6.96v-3.8h6.96     C496.84,485.82,498.58,487.51,498.58,489.62z M490.77,497.37v14.02h3.9v-14.02H490.77z M502,515.19v-29.37h7.81     c2.16,0,3.9,1.7,3.9,3.8v25.57h-3.9v-25.57h-3.9v25.57H502z M528.83,485.82v36.56h-3.9v-7.19h-3.9c-2.16,0-3.9-1.69-3.9-3.8     v-25.58h3.9v25.58h3.9v-25.58H528.83z"/>
                        </svg>`;
                break;
            case 2:
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="80" viewBox="29.39 355.295 537.1 133.3">
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
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="80" viewBox="77.3697 355.96 441.2 130.9">
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
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="80" viewBox="211.236 57.37 1181 623.5">
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#b41015" d="M 1368.96 325.67 v 48.08 h 21.14 v 49.72 h -21.14 c 0 9.99 1.55 16.87 4.66 20.65 c 3.18 3.77 8.97 5.66 17.38 5.66 v 49.47 l -5.41 0.12 c -10.66 0 -20.73 -2.48 -30.22 -7.43 c -9.39 -4.95 -16.95 -11.66 -22.66 -20.14 c -7.2 -10.83 -10.81 -25.17 -10.81 -43.05 V 325.67 H 1368.96 L 1368.96 325.67 z M 1299.62 497.48 h -47.08 v -70.87 c 0 -7.21 -2.97 -10.82 -8.94 -10.82 c -5.86 0 -8.8 3.61 -8.8 10.82 v 70.87 h -47.07 v -69.99 c 0 -16.36 5.4 -30.12 16.23 -41.28 c 10.83 -11.24 24.08 -16.86 39.78 -16.87 c 17.95 0 32.33 6.51 43.16 19.51 c 8.47 10.15 12.7 24.29 12.72 42.42 V 497.48 L 1299.62 497.48 z M 1168.22 442.34 h -71.37 c -1.1 -3.6 -1.63 -6.92 -1.63 -9.94 c 0 -4.19 0.76 -8.3 2.27 -12.34 h 23.91 c -1.68 -9.31 -6.93 -13.97 -15.75 -13.97 c -5.86 0 -10.66 2.51 -14.34 7.55 c -3.7 4.95 -5.54 11.33 -5.54 19.13 c 0 8.14 1.75 14.73 5.28 19.76 c 3.61 5.04 8.3 7.55 14.1 7.55 c 4.69 0 9.23 -2.35 13.6 -7.05 l 26.82 32.22 c -12.51 9.82 -26.4 14.72 -41.67 14.72 c -18.63 0 -34.28 -6.25 -46.95 -18.75 c -12.67 -12.5 -19.01 -27.99 -19.01 -46.45 c 0 -18.38 6.38 -33.89 19.13 -46.57 c 12.84 -12.67 28.57 -19 47.2 -19.01 c 18.2 0 33.47 6.21 45.81 18.63 c 12.43 12.33 18.63 27.56 18.63 45.69 C 1168.72 435.38 1168.54 438.32 1168.22 442.34 L 1168.22 442.34 z M 1031.02 370.73 v 50.35 c -3.27 -1.85 -6.25 -2.77 -8.94 -2.77 c -8.56 0 -12.84 6.55 -12.84 19.64 v 59.54 h -47.07 v -68.6 c 0 -18.13 4.95 -32.56 14.85 -43.3 c 9.9 -10.83 23.12 -16.24 39.64 -16.24 C 1020.36 369.34 1025.14 369.8 1031.02 370.73 L 1031.02 370.73 z M 948.32 370.73 v 50.35 c -3.27 -1.85 -6.25 -2.77 -8.93 -2.77 c -8.56 0 -12.84 6.55 -12.84 19.64 v 59.54 h -47.07 v -68.6 c 0 -18.13 4.95 -32.56 14.85 -43.3 c 9.9 -10.83 23.12 -16.24 39.65 -16.24 C 937.66 369.34 942.44 369.8 948.32 370.73 L 948.32 370.73 z M 793.76 415.79 c -5.04 0 -9.31 1.85 -12.84 5.54 c -3.53 3.61 -5.29 8.06 -5.29 13.34 c 0 5.2 1.76 9.65 5.29 13.34 c 3.61 3.7 7.89 5.54 12.84 5.54 c 5.04 0 9.31 -1.85 12.84 -5.54 c 3.61 -3.69 5.41 -8.14 5.42 -13.34 c 0 -5.29 -1.76 -9.73 -5.29 -13.34 C 803.2 417.64 798.88 415.79 793.76 415.79 L 793.76 415.79 z M 795.02 369.34 c 17.78 0 33.02 6.42 45.69 19.26 c 12.75 12.76 19.13 28.07 19.13 45.94 c 0 18.13 -6.46 33.57 -19.38 46.32 c -12.84 12.75 -28.36 19.12 -46.57 19.13 c -18.21 0 -33.77 -6.37 -46.7 -19.13 c -12.92 -12.84 -19.38 -28.28 -19.38 -46.32 c 0 -18.37 6.46 -33.81 19.38 -46.31 C 760.11 375.64 776.05 369.34 795.02 369.34 L 795.02 369.34 z M 691.18 325.67 v 48.08 h 21.15 v 49.72 H 691.18 c 0 9.99 1.55 16.87 4.66 20.65 c 3.19 3.77 8.97 5.66 17.37 5.66 v 49.47 l -5.42 0.12 c -10.66 0 -20.73 -2.48 -30.21 -7.43 c -9.4 -4.95 -16.95 -11.66 -22.66 -20.14 c -7.22 -10.83 -10.83 -25.17 -10.83 -43.05 V 325.67 H 691.18 L 691.18 325.67 z M284.33,63.29c22.83-0.16,25.78,11.05,25.78,11.05s7.36-15.47,28.72-15.47c21.36,0,24.31,15.47,24.31,15.47    s2.94-10.31,28.72-10.31c0,0,39.59,3.98,13.99,61.13c0,0-18.41,40.51-67.02,41.98c0,0-58.18,2.21-73.65-51.56    C265.18,115.58,248.62,63.55,284.33,63.29L284.33,63.29z M476.43,414.76c0,0-89.86,1.4-93.36-91.96h93.36V414.76L476.43,414.76z     M312.16,211.53c0-4.46,3.61-8.07,8.07-8.07c4.46,0,8.07,3.61,8.07,8.07c0,4.46-3.61,8.08-8.07,8.08    C315.78,219.6,312.16,215.98,312.16,211.53L312.16,211.53z M245.49,178.5c0,0,127.41-41.24,192.96,90.59    c0,0,31.67-118.58,153.93-118.58v404.64c0,0-5.04,124.25-138.41,124.25c-133.37,0-143.2-117.94-143.2-117.94V431.6    c0,0-42.12,55.46-80.02,18.25c-37.91-37.2-4.21-70.9-4.21-70.9s38.61-53.35,51.24-64.58c12.64-11.23,31.59-31.59,16.14-51.25    c-15.44-19.66-52.64-0.7-52.64-0.7l30.89-42.82L245.49,178.5L245.49,178.5z"/>
                        </svg>`;
                break;
            default:
                logo = `<svg xmlns="http://www.w3.org/2000/svg" height="80" viewBox="183.96 485.28 227.9 100.3">
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
        let content = '<div class="drag-icon"><span></span></div><div class="closePopUpButton" onclick="closePopupButton()"><span>X</span></div> <div class="popup-datos"><div>'+logo+'</div><h2 class="mt-5">'+nombre+'</h2><p>'+direccion+'</p><div class="separator-prods"></div></div><div class="popup-prods"><h3 class="mt-5 pb-3" style="text-align: center; border-bottom:1px solid">Productos</h3><div class="d-flex justify-content-center"><div class="prod_load spinner-border text-secondary" role="status"></div></div><ul id="ul_'+id_cliente+'" class="mt-4" style="max-height:310px;overflow-y:auto"></ul></div>';
        $('#'+id_cliente).empty();
        $('#'+id_cliente).append(content);
        let prods = "";
        if(xhr != null){
            xhr.abort();
        }
        xhr = $.ajax({
            url: "{{ route('getProducts') }}",
            type: 'GET',
            dataType: "json",
            data: {id_empresa: id_emp, id_cliente: id_cliente},
            beforeSend: function(){
                $('.prod_load').show();
            },
            success: function(response) {
                response.forEach(element => {
                    prods+='<li>'+element.name_cons+'</li>'
                });
            },
            statusCode: {
                403: function(){
                    alert("Intenta iniciar sesión nuevamente")
                }
            },
            complete:function(xhr, status){
                $('.prod_load').hide();
                $('#ul_'+id_cliente).append(prods);
            },
        });
    }

    function closePopupButton(){
        map.closePopup();
        $('#familias').css('z-index','1');
        if(!movil){
            $('#familias').css('width', '');
            $('#familias').css('padding', '');
            $('#familias').css('justify-content', '');
        }
    }

    async function fitBoundsPadding(e) {
        if(document.getElementById(this.id).hasChildNodes()){
            map.closePopup();
            $('#familias').css('z-index','1');
        } else {
            var id_emp = this.id_empresa;
            var nombre = this.nombre;
            var direccion = this.dir;
            var emp = this.emp;
            var id = this.id;

            console.log('=== CLICK EN MARCADOR ===');

            // Remover animación del marcador anterior
            removeAllAnimationClassFromMap();

            const transitionZoom = 6;
            const finalZoom = 15;
            const targetLatLng = e.target.getLatLng();

            try {
                // Paso 1: Zoom out
                console.log('Paso 1: Zoom out');
                await smoothZoomLeaflet(map, transitionZoom, 80);

                // Paso 2: Paneo
                console.log('Paso 2: Paneo');
                await smoothPanLeaflet(map, targetLatLng, 20, 50);

                // Paso 3: Zoom in
                console.log('Paso 3: Zoom in');
                await smoothZoomLeaflet(map, finalZoom, 80);

                // Paso 4: Mostrar popup y animación
                console.log('Paso 4: Mostrando popup');
                e.target._icon.classList.add('animation');
                e.target.openPopup(); // ← Abrir el popup MANUALMENTE aquí
                loadPopUp(id, id_emp, emp, direccion, nombre);
                // NO usar map.panTo aquí, ya hicimos el paneo arriba

                console.log('=== CLICK COMPLETADO ===');
            } catch (error) {
                console.error('Error en animación click:', error);
            }
        }
    }

    function removeAllAnimationClassFromMap() {
        // get all animation class on map
        const animations = document.querySelectorAll(".animation");
        animations.forEach((animation) => {
            animation.classList.remove("animation");
        });
        sheetContent.classList.remove('show');
    }

    function openNav() {
        map.closePopup();
        $('#familias').css('z-index','1');
        if(!movil){
            document.getElementById("mySidepanel").style.width = "400px";
            document.getElementById("familias").style.width= "calc(100dvw - 400px)";
            document.getElementById("familias").style.left= "400px";
            document.getElementById("map").style.setProperty('width', 'calc(100% - 400px)');
            document.getElementById("map").style.setProperty('transform', 'translateX(400px)');
        }else
            document.getElementById("mySidepanel").style.width = "100dvw";

    }

    function closeNav() {
        document.getElementById("mySidepanel").style.width = "0";
        document.getElementById("map").style.width = "100%";
        document.getElementById("map").style.setProperty('transform', 'translateX(0px)');
        if(!movil){
            document.getElementById("familias").style.width= "";
            document.getElementById("familias").style.left= "";
        }
    }

    // ========== FUNCIONES DE ANIMACIÓN PARA LEAFLET ==========

    // Función principal de animación con zoom out/in
    async function animateMarkerTransition(layer, id_cliente, id_emp, emp, direccion, nombre) {
        const transitionZoom = 12; // Zoom alejado para transición
        const finalZoom = 15; // Zoom final

        console.log('=== INICIANDO TRANSICIÓN LEAFLET ===');

        // Remover animación del marcador anterior
        removeAllAnimationClassFromMap();

        try {
            // Paso 1: Zoom out suave
            console.log('Paso 1: Zoom out');
            await smoothZoomLeaflet(map, transitionZoom, 80);

            // Paso 2: Mover al nuevo punto
            console.log('Paso 2: Paneo');
            const targetLatLng = layer.getLatLng();
            await smoothPanLeaflet(map, targetLatLng, 20, 50);

            // Paso 3: Zoom in suave
            console.log('Paso 3: Zoom in');
            await smoothZoomLeaflet(map, finalZoom, 80);

            // Paso 4: Mostrar popup y animación
            console.log('Paso 4: Mostrando popup');
            layer._icon.classList.add('animation');
            layer.openPopup();
            updateSheetHeight(40);
            loadPopUp(id_cliente, id_emp, emp, direccion, nombre);

            console.log('=== TRANSICIÓN COMPLETADA ===');
        } catch (error) {
            console.error('Error en animación:', error);
        }
    }

    // Zoom suave por pasos para Leaflet
    function smoothZoomLeaflet(map, targetZoom, stepDelay = 60) {
        return new Promise((resolve) => {
            const currentZoom = map.getZoom();
            const step = currentZoom < targetZoom ? 0.3 : -0.3;
            let zoom = currentZoom;

            console.log(`Zoom Leaflet de ${currentZoom} a ${targetZoom}`);

            function nextStep() {
                const remaining = Math.abs(targetZoom - zoom);

                if (remaining < Math.abs(step)) {
                    map.setZoom(targetZoom);
                    console.log('Zoom completado');
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

    // Paneo suave por pasos para Leaflet
    function smoothPanLeaflet(map, targetLatLng, steps = 25, stepDelay = 40) {
        return new Promise((resolve) => {
            const startLatLng = map.getCenter();
            const latDiff = targetLatLng.lat - startLatLng.lat;
            const lngDiff = targetLatLng.lng - startLatLng.lng;

            let currentStep = 0;

            console.log(`Pan Leaflet en ${steps} pasos`);

            function nextStep() {
                if (currentStep >= steps) {
                    map.panTo(targetLatLng, { animate: false });
                    console.log('Pan completado');
                    resolve();
                    return;
                }

                currentStep++;

                // Easing para suavidad
                const progress = currentStep / steps;
                const easeProgress = progress < 0.5
                    ? 2 * progress * progress
                    : 1 - Math.pow(-2 * progress + 2, 2) / 2;

                const newLat = startLatLng.lat + (latDiff * easeProgress);
                const newLng = startLatLng.lng + (lngDiff * easeProgress);

                map.panTo([newLat, newLng], { animate: false });

                setTimeout(nextStep, stepDelay);
            }

            nextStep();
        });
    }

    // ========== FIN FUNCIONES DE ANIMACIÓN ==========

</script>
@endpush
@endsection
