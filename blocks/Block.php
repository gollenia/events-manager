<?php

namespace Contexis\Events\Blocks;

$assets = Assets::init();

Upcoming::init($assets);
Featured::init($assets);
Details::init($assets);
Booking::init($assets);