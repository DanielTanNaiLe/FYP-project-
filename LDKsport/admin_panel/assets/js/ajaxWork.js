function showProductItems(){  
    $.ajax({
        url:"./adminView/viewAllProducts.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}
function showCategory(){  
    $.ajax({
        url:"./adminView/viewCategories.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}
function showBrand(){  
    $.ajax({
        url:"./adminView/viewAllBrands.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}
function showSizes(){  
    $.ajax({
        url:"./adminView/viewSizes.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}
function showProductSizes(){  
    $.ajax({
        url:"./adminView/viewProductSizes.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function showCustomers(){
    $.ajax({
        url:"./adminView/viewCustomers.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function showGender(){
    $.ajax({
        url:"./adminView/viewGender.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function showOrders(){
    $.ajax({
        url:"./adminView/viewAllOrders.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}
function showReport(){  
    $.ajax({
        url:"./adminView/viewReports.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function showFeedback(){  
    $.ajax({
        url:"./adminView/viewFeedback.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function showProfile(){  
    $.ajax({
        url:"./adminView/viewProfile.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function showAdmin(){  
    $.ajax({
        url:"./adminView/viewAdmin.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function showPromocde(){  
    $.ajax({
        url:"./adminView/viewPromocode.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}



function ChangeOrderStatus(id){
    $.ajax({
       url:"./controller/updateOrderStatus.php",
       method:"post",
       data:{record:id},
       success:function(data){
           alert('Order Status updated successfully');
           $('form').trigger('reset');
           showOrders();
       }
   });
}

function ChangePay(id){
    $.ajax({
       url:"./controller/updatePayStatus.php",
       method:"post",
       data:{record:id},
       success:function(data){
           alert('Payment Status updated successfully');
           $('form').trigger('reset');
           showOrders();
       }
   });
}

function registerAdmin(){
    $.ajax({
       url:"./adminView/registerAdmin.php",
       method:"post",
       data:{record:id},
       success:function(data){
        $('.allContent-section').html(data);
       }
   });
}


function addItems() {
    var p_name = $('#p_name').val();
    var p_desc = $('#p_desc').val();
    var p_price = $('#p_price').val();
    var category = $('#category').val();
    var brand = $('#brand').val();
    var gender = $('#gender').val();
    var file = $('#file')[0].files[0];
    var file2 = $('#file2')[0].files[0];  // Secondary image
    var file3 = $('#file3')[0].files[0];  // Tertiary image

    var fd = new FormData();
    fd.append('p_name', p_name);
    fd.append('p_desc', p_desc);
    fd.append('p_price', p_price);
    fd.append('category', category);
    fd.append('brand', brand);
    fd.append('gender', gender);
    fd.append('file', file);
    if (file2) fd.append('file2', file2);  // Append secondary image if exists
    if (file3) fd.append('file3', file3);  // Append tertiary image if exists

    $.ajax({
        url: "./controller/addItemController.php",
        method: "post",
        data: fd,
        processData: false,
        contentType: false,
        success: function(data) {
            alert('Product added successfully.');
            $('form').trigger('reset');
            showProductItems();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error adding product: ' + textStatus + ' - ' + errorThrown);
        }
    });
}




//edit product data
function itemEditForm(id){
    $.ajax({
        url:"./adminView/editItemForm.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function updateItems(event) {
    event.preventDefault(); // Prevent form submission

    var product_id = $('#product_id').val();
    var p_name = $('#p_name').val();
    var p_desc = $('#p_desc').val();
    var p_price = $('#p_price').val();
    var category = $('#category').val();
    var gender = $('#gender').val();
    var brand = $('#brand').val();
    var existingImage = $('#existingImage').val();
    var newImage = $('#newImage')[0].files[0];
    var existingImage2 = $('#existingImage2').val();
    var newImage2 = $('#newImage2')[0].files[0];
    var existingImage3 = $('#existingImage3').val();
    var newImage3 = $('#newImage3')[0].files[0];

    var fd = new FormData();
    fd.append('product_id', product_id);
    fd.append('p_name', p_name);
    fd.append('p_desc', p_desc);
    fd.append('p_price', p_price);
    fd.append('category', category);
    fd.append('gender', gender);
    fd.append('brand', brand);
    fd.append('existingImage', existingImage);
    if (newImage) fd.append('newImage', newImage);
    fd.append('existingImage2', existingImage2);
    if (newImage2) fd.append('newImage2', newImage2);
    fd.append('existingImage3', existingImage3);
    if (newImage3) fd.append('newImage3', newImage3);

    $.ajax({
        url: './controller/updateItemController.php',
        method: 'post',
        data: fd,
        processData: false,
        contentType: false,
        success: function(data) {
            alert('Data update success.');
            $('form').trigger('reset');
            showProductItems();
        }
    });
}
//delete product data
function itemDelete(id){
    $.ajax({
        url:"./controller/deleteItemController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Items Successfully deleted');
            $('form').trigger('reset');
            showProductItems();
        }
    });
}


//delete cart data
function cartDelete(id){
    $.ajax({
        url:"./controller/deleteCartController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Cart Item Successfully deleted');
            $('form').trigger('reset');
            showMyCart();
        }
    });
}

function eachDetailsForm(id){
    $.ajax({
        url:"./view/viewEachDetails.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}



//delete category data
function categoryDelete(id){
    $.ajax({
        url:"./controller/catDeleteController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Category Successfully deleted');
            $('form').trigger('reset');
            showCategory();
        }
    });
}

//delete brand data
function brandDelete(id){
    $.ajax({
        url:"./controller/brandDeleteController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Brand Successfully deleted');
            $('form').trigger('reset');
            showBrand();
        }
    });
}

//delete gender data
function genderDelete(id){
    $.ajax({
        url:"./controller/genderDeleteController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Gender Successfully deleted');
            $('form').trigger('reset');
            showGender();
        }
    });
}

//delete user data
function genderDelete(id){
    $.ajax({
        url:"./controller/userDeleteController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('User Successfully deleted');
            $('form').trigger('reset');
            showGender();
        }
    });
}

//delete size data
function sizeDelete(id){
    $.ajax({
        url:"./controller/deleteSizeController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Size Successfully deleted');
            $('form').trigger('reset');
            showSizes();
        }
    });
}


//delete variation data
function variationDelete(id){
    $.ajax({
        url:"./controller/deleteVariationController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Successfully deleted');
            $('form').trigger('reset');
            showProductSizes();
        }
    });
}

//delete variation data
function userDelete(id){
    $.ajax({
        url:"./controller/deleteUserController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Successfully deleted');
            $('form').trigger('reset');
            showCustomers();
        }
    });
}

//delete variation data
function adminDelete(id){
    $.ajax({
        url:"./controller/deleteAdminController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Successfully deleted');
            $('form').trigger('reset');
            showAdmin();
        }
    });
}

//edit variation data
function variationEditForm(id){
    $.ajax({
        url:"./adminView/editVariationForm.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}


//update variation after submit
function updateVariations(){
    var v_id = $('#v_id').val();
    var product = $('#product').val();
    var size = $('#size').val();
    var qty = $('#qty').val();
    var fd = new FormData();
    fd.append('v_id', v_id);
    fd.append('product', product);
    fd.append('size', size);
    fd.append('qty', qty);
   
    $.ajax({
      url:'./controller/updateVariationController.php',
      method:'post',
      data:fd,
      processData: false,
      contentType: false,
      success: function(data){
        alert('Update Success.');
        $('form').trigger('reset');
        showProductSizes();
      }
    });
}

function toggleUpdateForm() {
    var form = document.getElementById('updateProfileForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function updateProfile() {
    var adminId = $('#adminId').val();
    var adminName = $('#adminName').val();
    var adminEmail = $('#adminEmail').val();
    var oldPassword = $('#oldPassword').val();
    var newPassword = $('#newPassword').val();
    var confirmPassword = $('#confirmPassword').val();

    if (newPassword !== confirmPassword) {
        alert('New password and confirm password do not match.');
        return;
    }

    var fd = new FormData();
    fd.append('admin_id', adminId);
    fd.append('admin_name', adminName);
    fd.append('admin_email', adminEmail);
    fd.append('old_password', oldPassword);
    fd.append('new_password', newPassword);

    $.ajax({
        url: './controller/updateProfile.php',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function(response) {
            alert(response);
            if (response.includes('successfully')) {
                location.reload(); // Reload the page to reflect changes
            }
        }
    });
}


function search(id){
    $.ajax({
        url:"./controller/searchController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('.eachCategoryProducts').html(data);
        }
    });
}


function quantityPlus(id){ 
    $.ajax({
        url:"./controller/addQuantityController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('form').trigger('reset');
            showMyCart();
        }
    });
}
function quantityMinus(id){
    $.ajax({
        url:"./controller/subQuantityController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('form').trigger('reset');
            showMyCart();
        }
    });
}

function checkout(){
    $.ajax({
        url:"./view/viewCheckout.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}


function removeFromWish(id){
    $.ajax({
        url:"./controller/removeFromWishlist.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Removed from wishlist');
        }
    });
}


function addToWish(id){
    $.ajax({
        url:"./controller/addToWishlist.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Added to wishlist');        
   }
});
}


