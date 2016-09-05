<div class="home-slider">
    <!-- Carousel -->
    <div id="home-slider" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#home-slider" data-slide-to="0" class="active"></li>
            <li data-target="#home-slider" data-slide-to="1"></li>
            <li data-target="#home-slider" data-slide-to="2"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <!-- Slide #1 -->
            <div class="item active" id="item-1">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="home-slider__content">
                                <br><br>
                                <h1 class="first-child animated slideInDown delay-2">Bienvenido</h1>
                                <h3 class="animated slideInDown delay-3">Te saludamos en el nombre de nuestro Señor Jesucristo</h3>

                                <ul class="lead string">
                                    <li class="animated fadeInUpBig delay-6"><span>Que su paz que sobrepasa todo entendimiento guarde tu coraz&oacute;n y tus pensamientos ...</span></li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- / .row -->
                </div> <!-- / .container -->
                <div class="bg-img hidden-xs">
                    <img src="{{ URL::asset('assets/img/slide-1.png') }}" alt="...">
                </div>
            </div> <!-- / .item -->
            <!-- Slide #2 -->
            <div class="item" id="item-2">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="home-slider__content">
                                <br><br>
                                <h1 class="first-child animated slideInDown delay-2">Compartamos</h1>
                                <h3 class="animated slideInDown delay-3">Te invitamos a compartir lo que la palabra de Dios pone en tu corazón</h3>
                                <p class="text-muted animated slideInLeft delay-4">
                                    ... que prediques la palabra; que instes a tiempo y fuera de tiempo; redarguye, reprende, exhorta con toda paciencia y doctrina.
                                </p>
                            </div>
                        </div>
                    </div> <!-- / .row -->
                </div> <!-- / .container -->
                <div class="bg-img hidden-xs">
                    <img src="{{ URL::asset('assets/img/slide-2.png') }}" alt="...">
                </div>
            </div> <!-- / .item -->
            <!-- Slide #3 -->
            <div class="item" id="item-3">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="home-slider__content">
                                <h1 class="first-child animated slideInDown delay-2">Esfuérzate y sé valiente</h1>
                                <h3 class="animated slideInDown delay-3">Podrás : </h3>
                                <ul>
                                    <li class="animated slideInLeft delay-4"><i class="fa fa-chevron-circle-right fa-fw"></i> Seguir un plan de lectura</li>
                                    <li class="animated slideInLeft delay-5"><i class="fa fa-chevron-circle-right fa-fw"></i> Preparar tu artículo bíblico</li>
                                    <li class="animated slideInLeft delay-6"><i class="fa fa-chevron-circle-right fa-fw"></i> Comentar los versículos</li>
                                    <li class="animated slideInLeft delay-7"><i class="fa fa-chevron-circle-right fa-fw"></i> Leer diferentes versiones bíblicas</li>
                                </ul>

                            </div>
                        </div>
                    </div> <!-- / .row -->
                </div> <!-- / .container -->
                <div class="bg-img hidden-xs">
                    <img src="{{ URL::asset('assets/img/slide-3.png') }}" alt="...">
                </div>
            </div> <!-- / .item -->
        </div> <!-- / .carousel -->
        <!-- Controls -->
        <a class="carousel-arrow carousel-arrow-prev" href="#home-slider" data-slide="prev">
            <i class="fa fa-angle-left"></i>
        </a>
        <a class="carousel-arrow carousel-arrow-next" href="#home-slider" data-slide="next">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div> <!-- / .home-slider -->