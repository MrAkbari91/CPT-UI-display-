<?php
// Add Settings Page
function business_terms_settings_page()
{
    ob_start();
    ?>
    <div>
        <h1>Settings Page</h1>
        <div>
            <div class="box">
                <h2>display settings</h2>
                <form action="post">
                    <div>
                        <div>
                            <input type="radio" name="display" value="grid"> box
                        </div>
                        <p>Display terms per row </p>
                        <input type="number" name="display" value="4">

                    </div>
                    <div>
                        <input type="radio" name="display" value="list"> list
                    </div>
                    <div>
                        <input type="radio" name="display" value="card"> card
                    </div>
                    <input type="submit" value="save">
                </form>
            </div>
        </div>
    </div>
    <?php
}



