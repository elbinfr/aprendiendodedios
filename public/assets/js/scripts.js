/*
*Para mostrar la imagen cargada desde inputfile, todos seran de la clase custom-image
*/
$('.custom-image').fileinput(
  {
    browseLabel: 'Buscar',
    uploadAsync: false,
    minFileCount: 1,
    maxFileCount: 1,
    showUpload: false,
    showRemove: false
  }
);

$('.fecha').datepicker(
  {
    language: "es",
    autoclose: true,
    todayHighlight: true
  }
);

/*
*COMOBOX CON AUTOCOMPLETE
 */
$('.custom-select').selectpicker({});

/*
* Combo multiselect
*/
$('.multiselect').multiSelect();
