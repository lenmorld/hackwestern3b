
   function previewFile(){
       var preview = document.querySelector('#upload'); //selects the query named img
       var file    = document.querySelector('input[type=file]').files[0]; //sames as here
       var reader  = new FileReader();


       //var outputLink = $('<img/>').attr('src', "https://en.wikipedia.org/wiki/" + json.query.search[i].title);

       var Counter = 0; //Counts the amount of images in container

       reader.onloadend = function () {
           preview.src = reader.result;
       }

       if (file) {
           reader.readAsDataURL(file); //reads the data as a URL
       } else {
           preview.src = "";
       }

       if (Counter > 6){
          showScrollBar();
       }
       console.log(reader);

  }

  previewFile();  //calls the function named previewFile()

  function showScrollBar(){

    //when Counter = 7 show scroll bar 
  }



  