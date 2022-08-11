//[advanced form element Javascript]

//Project:	Apro Admin - Responsive Admin Template
//Version:	1.1
//Last change:  10/10/2017
//Primary use:   Used only for the advanced-form-element

$(function () {
    "use strict";

    //Initialize Select2 Elements
    // $('.select2').select2();

    //Datemask dd/mm/yyyy
    // $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
    //Datemask2 mm/dd/yyyy
    // $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
    //Money Euro
    // $('[data-mask]').inputmask();

    
    //Date range picker with time picker
    // $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' });
    //Date range as a button
    // $('#daterange-btn').daterangepicker(
    //   {
    //     ranges   : {
    //       "Aujourd'hui"       : [moment(), moment()],
    //       'Hier'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //       '7 Derniers jours' : [moment().subtract(6, 'days'), moment()],
    //       '30 Derniers jours': [moment().subtract(29, 'days'), moment()],
    //       'Ce mois'  : [moment().startOf('month'), moment().endOf('month')],
    //       'Le mois dernier'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //     },
        // startDate: moment().subtract(29, 'days'),
        // endDate  : moment()
    //   },
    //   function (start, end) {
    //     $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    //   }
    // );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    // $('.ichack-input input[type="checkbox"].minimal, .ichack-input input[type="radio"].minimal').iCheck({
    //   checkboxClass: 'icheckbox_minimal-blue',
    //   radioClass: 'icheckbox_minimal-blue'
    // });
    // //Red color scheme for iCheck
    // $('.ichack-input input[type="checkbox"].minimal-red, .ichack-input input[type="radio"].minimal-red').iCheck({
    //   checkboxClass: 'icheckbox_minimal-red',
    //   radioClass   : 'iradio_minimal-red'
    // });
    // //Flat red color scheme for iCheck
    // $('.ichack-input input[type="checkbox"].flat-red, .ichack-input input[type="radio"].flat-red').iCheck({
    //   checkboxClass: 'icheckbox_flat-green',
    //   radioClass   : 'iradio_flat-green'
    // });

    //Colorpicker
    // $('.my-colorpicker1').colorpicker();
    // //color picker with addon
    // $('.my-colorpicker2').colorpicker();

    //Timepicker
    // $('.timepicker').timepicker({
    //   showInputs: false
    // });
  });
