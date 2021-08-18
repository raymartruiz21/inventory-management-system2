var SmartMultiFiled = (function(){
    var rowcount, html, addBtn, tableBody;

    addBtn = $("#addNew");
    rowcount = $("#autocomplete_table tbody tr").length+1;
    tableBody = $("#autocomplete_table tbody");


    function formHtml() {
        html = '<tr id="row_'+rowcount+'">';
        html += '<td>';
        html += '<input type="text" data-type="code" class="form-control form-control-sm autocomplete_txt" name="code[]" id="code_'+rowcount+'" placeholder="QRcode">';
        html += '</td>';

        html += '<td>';
        html += 'input type="text" data-type="name" id="name_'+rowcount+'" class="form-control form-control-sm autocomplete_txt" name="name[]" placeholder="Product Name">';
        html += '</td>';

        html += '<td>';
        html += '<input type="text" data-type="price" class="form-control form-control-sm autocomplete_txt" name="price[]" id="price_'+rowcount+'" readonly>';
        html += '</td>';

        html += '<td>';
        html += '<input type="number" data-type="quantity" class="form-control form-control-sm autocomplete_txt" name="quantity[]" id="quantity_'+rowcount+'" placeholder="Qty">';
        html += '</td>';

        html += '<td>';
        html += '<button type="button" name="remove" id="delete_'+rowcount+'" class="btn btn-danger btn-sm delete_row">-</button>';
        html += '</td>';

        html += '</tr>';
        rowcount++;
        return html;
    }
    function getFieldNo(type){
        var fieldNo;
        switch (type) {
            case 'code':
                fieldNo = 0;
                break;
            case 'name':
                fieldNo = 1;
                break;
            case 'price':
                fieldNo = 2;
                break;
            default:
                break;
        }
        return fieldNo;
    }

    function handleAutocomplete() {
        var type, fieldNo, currentEle; 
        type = $(this).data('type');
        fieldNo = getFieldNo(type);
        currentEle = $(this);

        if(typeof fieldNo === 'undefined') {
            return false;
        }

        $(this).autocomplete({
            source: function( data, cb ) {	 
                $.ajax({
                    url:'ajax.php',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        name:  data.term,
                        code: fieldNo
                    },
                    success: function(res){
                        var result;
                        result = [
                            {
                                label: 'There is matching record found for '+data.term,
                                value: ''
                            }
                        ];

                        if (res.length) {
                            result = $.map(res, function(obj){
                                var arr = obj.split("|");
                                return {
                                    label: arr[fieldNo],
                                    value: arr[fieldNo],
                                    data : obj
                                };
                            });
                        }
                        cb(result);
                    }
                });
            },
            autoFocus: true,	      	
            minLength: 1,
            select: function( event, ui ) {
                var resArr, rowNo;
                
                
                rowNo = getId(currentEle);
                resArr = ui.item.data.split("|");	
                
            
                $('#code_'+rowNo).val(resArr[0]);
                $('#name_'+rowNo).val(resArr[1]);
                $('#price_'+rowNo).val(resArr[2]);
            }		      	
        });
    }

    function getId(element){
        var id, idArr;
        id = element.attr('id');
        idArr = id.split("_");
        return idArr[idArr.length - 1];
    }

    function addNewRow() { 
        tableBody.append( formHtml() );
    }

    function deleteRow() { 
        var currentEle, rowNo;
        currentEle = $(this);
        rowNo = getId(currentEle);
        $("#row_"+rowNo).remove();
    }

    function registerEvents() {
        addBtn.on("click", addNewRow);
        $(document).on('click', '.delete_row', deleteRow);
        //register autocomplete events
        $(document).on('focus','.autocomplete_txt', handleAutocomplete);
    }
    function init() {
        registerEvents();
    }

    return {
        init: init
    };
})();

$(document).ready(function(){
    SmartMultiFiled.init();
});