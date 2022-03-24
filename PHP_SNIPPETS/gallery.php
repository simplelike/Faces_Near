<script src="/js/galleryPageHandler.js"></script>

<?php require_once('./PHP_SNIPPETS/usageOfLocalDumpInfoDiv.php') ?>
<div class="preloader">
    <div class="preloader__image"></div>
</div>

<div class="container marginContent">
    <div class="row">
        <div class="col box">
            <select id="jeweleryInputParamElement">
                <option value="default" selected></option>
            </select>
        </div>
        <div class="col box">
            <select id="backgroundInputParamElement">
                <option value="default" selected></option>
            </select>

        </div>
        <div class="col box">
            <select id="faceInputParamElement">
                <option value="default" selected></option>
            </select>
        </div>
        <div class="col box">
            <select id="maskInputParamElement">
                <option value="default" selected></option>
            </select>
        </div>

    </div>
    <div class="row marginContent">
        <div class="col box">
            <select id="hatInputParamElement">
                <option value="default" selected></option>
            </select>
        </div>
        <div class="col box">
            <select id="shirtInputParamElement">
                <option value="default" selected></option>
            </select>
        </div>
        <div class="col box">
            <select id="lipsInputParamElement">
                <option value="default" selected></option>
            </select>
        </div>
        <div class="col box">
            <select id="eyesInputParamElement">
                <option value="default" selected></option>
            </select>
        </div>
    </div>
</div>
<div class="container marginContent">
    <div class="row marginContent">
        <div class="col">
            <div class="attrComponent" id="jeweleryAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="backgroundAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="faceAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="maskAttrComponent"></div>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col">
            <div class="attrComponent" id="hatAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="shirtAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="lipsAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="eyesAttrComponent"></div>
        </div>
    </div>
</div>
<div class="container marginBottom marginContent">
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 centerContent" id="infiniteScroll"></div>
</div>

<div class="loading">
    <div class="ball"></div>
    <div class="ball"></div>
    <div class="ball"></div>
</div>