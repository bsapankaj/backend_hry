$(document).on('click','.nav a',function(e){
    $(".nav").find(".active").removeClass("active");
    $(this).addClass("active");
    // let url = window.location.href;
    // let url = this.href;
    // $(document).find(".nav-link").addClass("active")
   
    // console.log(e.target);


  })
