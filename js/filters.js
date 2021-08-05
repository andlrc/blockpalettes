$(document).ready(function(){
    $('.filter-box input[type="button"]').on("click", function(){
        /* Get input value on change */
        let filterVal = $(this).val();
        let resultDropdown = $(this).siblings(".result");
        localStorage.setItem('dateFilter', filterVal);
        if(filterVal.length){

            $.get("include/logic.php", {dateFilter: filterVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
        $(this).parents(".filter-box").find('input[type="button"]').val($(this).text());
        $(this).parent(".result").empty();
    });
});
