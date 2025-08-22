<footer id="footer" data-footer-style="3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 d-flex justify-content-start">
                <div class="d-flex">
                    <div class="d-flex mr-30">
                        <?php echo view('public/inc/footerLogoSettings.php'); ?>
                    </div>
                    <div class="d-flex">
                        <p class="mb-0">© <?php echo date('Y')?> All rights reserved.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
                <div class="d-flex">
                    <ul class="social-icons sc--clean clearfix">
						<li class="title">GET SOCIAL</li>
                        <?php echo view('public/inc/socialSettings.php'); ?>
					</ul>
                </div>
            </div>
        </div>
    </div>
</footer>



