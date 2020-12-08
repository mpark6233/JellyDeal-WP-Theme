<?php /* Template Name: CustomPageT1 */ ?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post();
 
            // Include the page content template.
            get_template_part( 'template-parts/content', 'page' );
 
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
 
            // End of the loop.
        endwhile;
        ?>

        <!-- Start Custom Form -->
        <div class="container">

            <h2>서비스 신청하기</h2>

            <div id="mocha"></div>

            <!-- Test HTML -->
            <div id="main">
                <!-- form1 -->
                <form id="form1" action="ajax/text.html" method="get">
                    <div>
                        <input type="hidden" name="Hidden" value="hiddenValue">
                        <input name="Name" type="text" value="MyName1">
                        <input name="Password" type="password">
                        <select name="Multiple" multiple="multiple">
                            <optgroup label="block 1">
                                <option value="one" selected="selected">One</option>
                                <option value="two">Two</option>
                                <option value="three">Three</option>
                            </optgroup>
                            <optgroup label="block 2">
                                <option value="four" selected="selected">Four</option>
                                <option value="five">Five</option>
                                <option value="six" selected="selected">Six</option>
                            </optgroup>
                        </select>
                        <select name="Single">
                            <option value="one" selected="selected">One</option>
                            <option value="two">Two</option>
                            <option value="three">Three</option>
                        </select>
                        <select name="Single2">
                            <optgroup label="block 3">
                                <option value="A" selected="selected">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </optgroup>
                            <optgroup label="block 3">
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </optgroup>
                        </select>
                        <input type="checkbox" name="Check" value="1">
                        <input type="checkbox" name="Check" value="2" checked="checked">
                        <input type="checkbox" name="Check" value="3">
                        <input type="radio" name="Radio" value="1">
                        <input type="radio" name="Radio" value="2" checked="checked">
                        <input type="radio" name="Radio" value="3">
                        <input type="text" name="action" value="1">
                        <input type="text" name="method" value="2">
                        <textarea name="Text" rows="2" cols="20">This is Form1</textarea>
                        <input type="submit" name="submitButton" value="Submit1">
                        <input type="submit" name="submitButton" value="Submit2">
                        <input type="submit" name="submitButton" value="Submit3">
                        <input type="image" name="submitButton" value="Submit4" src="img/submit.gif">
                        <input type="reset" name="resetButton" value="Reset">
                    </div>
                </form>

                <!-- form2 -->
                <form id="form2" action="ajax/text.html" method="get">
                    <div>
                        <input name="a" type="text">
                        <textarea name="b" rows="1" cols="1"></textarea>
                        <input name="c" type="text">
                        <select name="d">
                            <option value="x">x</option>
                        </select>
                        <textarea name="e" rows="1" cols="1"></textarea>
                        <input name="f" type="text">
                        <input type="reset" name="resetButton" value="Reset">
                    </div>
                </form>

                <!-- form3 -->
                <form id="form3" action="ajax/text.html" method="post">
                    <div>
                        <input name="a" type="text">
                    </div>
                </form>

                <!-- form4 -->
                <form id="form4" action="ajax/text.html" method="get">
                    <div>
                        <input name="a" type="text">
                        <input type="submit" id="submitForm4">
                        <input type="submit" id="submitForm4withName" name="form4inputName">
                        <input type="image" id="form4imageSubmit" name="myImage" src="img/submit.gif">
                    </div>
                </form>

                <!-- form5 -->
                <form id="form5" action="ajax/text.html?test=form" method="get">
                    <div>
                        <input name="a" type="text">
                        <input type="submit">
                        <input type="submit" name="form5inputName">
                        <input type="image" id="form5imageSubmit" name="myImage" src="img/submit.gif">
                    </div>
                </form>

                <!-- form6, 'option' testing -->
                <form id="form6" action="ajax/text.html" method="get">
                    <div>
                        <select name="A">
                            <option value="" selected="selected">EMPTY_STRING</option>
                            <!-- TEST A: value === empty string -->
                        </select>
                        <select name="B">
                            <option selected="selected">MISSING_ATTR</option> <!-- TEST B: no value attr -->
                        </select>
                        <select name="C" multiple="multiple">
                            <option value="" selected="selected">EMPTY_STRING</option>
                            <option selected="selected">MISSING_ATTR</option>
                        </select>
                    </div>
                </form>

                <!-- form7, 'enctype' testing -->
                <form id="form7" action="ajax/text.html" method="post" enctype="text/css">
                    <div>
                        <textarea name="doc">body { padding: 0}</textarea>
                    </div>
                </form>

                <form id="form8" action="ajax/text.html" method="post">
                    <div>
                        <textarea name="ta">blah</textarea>
                        <input type="submit" id="submitForm8">
                    </div>
                </form>

                <form id="form9" action="ajax/text.html" method="post">
                    <div>
                        <textarea name="ta">blah</textarea>
                        <input type="submit" id="submitForm9">
                    </div>
                </form>

                <!-- form10, outside fields -->
                <form id="form10">
                    <input type="text" name="insideForm">
                </form>
                <input type="text" name="outsideForm" form="form10">

                <!-- pseudo-form -->
                <div id="pseudo">
                    <input name="a" type="text">
                    <input type="checkbox" name="Check" value="1">
                    <input type="checkbox" name="Check" value="2" checked="checked">
                    <select name="Select">
                        <option value="opt1">
                        <option value="opt2" selected="selected">
                    </select>
                    <input type="submit" value="button">
                </div>

                <form style="display:none" id="fieldTest" action="ajax/text.html" method="post">
                    <div>
                        <select name="A">
                            <option value="1" selected="selected">ONE</option>
                            <option value="2">TWO</option>
                        </select>
                        <select name="B" multiple="multiple">
                            <option value="3" selected="selected">ONE</option>
                            <option value="4" selected="selected">TWO</option>
                            <option value="4b">THREE</option>
                        </select>
                        <input name="C" type="hidden" value="5">
                        <input name="D" type="text" value="6">
                        <textarea name="E">7</textarea>
                        <input name="F" type="checkbox" value="8" checked="checked">
                        <input name="F" type="checkbox" value="9">
                        <input name="F" type="checkbox" value="10" checked="checked">
                        <input name="G" type="radio" value="11">
                        <input name="G" type="radio" value="12" checked="checked">
                        <input name="G" type="radio" value="13">
                        <input name="H" type="password" value="14">
                        <input name="I" type="submit" value="15">
                        <input name="J" type="reset" value="16">
                    </div>
                </form>

                <form style="display:none" id="actionTest1" action="#" method="post">
                    <div>
                        <input name="A" type="hidden" value="1">
                    </div>
                </form>
                <form style="display:none" id="actionTest2" action="#blah" method="post">
                    <div>
                        <input name="A" type="hidden" value="1">
                    </div>
                </form>
                <form style="display:none" id="actionTest3" action="" method="post">
                    <div>
                        <input name="A" type="hidden" value="1">
                    </div>
                </form>
                <form style="display:none" id="actionTest4" method="post">
                    <div>
                        <input name="A" type="hidden" value="1">
                    </div>
                </form>

                <div id="targetDiv"></div>
            </div>

        </div><!-- .container -->


        <!-- mocha -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="../src/jquery.form.js"></script>
        <script src="../node_modules/mocha/mocha.js"></script>
        <script src="../node_modules/chai/chai.js"></script>
        <script>
            // This will be overridden by mocha-helper if you run with grunt
            mocha.setup('bdd');
            mocha.reporter('html');
        </script>

        <script src="test.js"></script>

        <!-- run mocha -->
        <script>
            // Only tests run in real browser, injected script run if options.run == true
            if (navigator.userAgent.indexOf('PhantomJS') < 0) {
                mocha.run();
            }
        </script>

        <!-- End Custom Form -->

    </main><!-- .site-main -->
</div><!-- .content-area -->

<!-- Custom Form Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"
    integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous">
</script>

<?php get_sidebar(); ?>
<?php get_footer(); ?>