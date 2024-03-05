<?php
session_name('Clu5TerM2021');
session_start();
session_unset();
session_destroy();

header('Location: login.html');