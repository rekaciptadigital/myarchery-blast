<?php
if (empty($products) || $products === false || $products === null) {
    echo '<div class="alert alert-warning">No plugins available or failed to load plugin data.</div>';
} else {
    _ec($products);
}
