<br>
<a href="index.php?deleteProductInfo=1&product_id=*product_id*">Delete</a>
<form action="index.php" method="get">
    <input type="text" name="addProductInfo" value="1" hidden="hidden"/>
    <input type="text" name="product_id" value="*product_id*" hidden="hidden"/>
    <input type="text" name="company" placeholder="Company"/>
    <input type="text" name="country" placeholder="Country"/>
    <input type="text" name="description" placeholder="Description"/>
    <input type="text" name="bought" placeholder="Bought"/>
    <input type="text" name="remaining" placeholder="Remaining"/>
    <input type="submit" value="Add"/>
</form>