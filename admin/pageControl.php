<?php

class pageControl
{
    public function shopOpenned() {
        $shopOpen = true;

        if ($shopOpen == false) {
            header("Location: pages/LoadingPage.php");
        }
    }
}
?>