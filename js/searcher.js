//

const search = () => {
    let keywords = $('#searcher_input').val();
    if(keywords == ''){

    } else {
        $('.search-results-for').html('<i class="fa fa-spin fa-spinner"></i>');
        $('#search-result-statistics').html('<i class="fa fa-spin fa-spinner"></i>');
        $('#search-result-statistics').html('<i class="fa fa-spin fa-spinner"></i>');
        var data = {
            keywords : keywords,
            action: "search"
        };
        data = $(this).serialize() + "&" + $.param(data);
        $.ajax({
            url: "model/searcher.php",
            type: "POST",
            data: data,
            dataType: "json",
        })        
        .done(function(data){
            var obj = JSON && JSON.parse(data["json"]) || $.parseJSON(data["json"]);
            var process = obj.process;
            var status = obj.status;
            var data = obj.data;
            if(process == '1' && status == false){
               $('#search-result-state').html(data.msg);
                
            }
            if(process == '1' && status == true){
                let searchKeywords = '';
                let statisticsRows = '';
                let searchRows = '';
                if(data.html.items == ''){
                    statisticsRows += `<tr class="odd gradeX">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>`;
                    searchRows += `<tr class="odd gradeX">
                                    <td>
                                        <a><b>No hubo resultados</b></a><br>
                                        <a>Vuelva a buscar con otras palabras</a>
                                    </td>
                                </tr>`;
                } else {
                    searchKeywords = data.keywords
                    data.html.statistics.forEach((result) => {
                        statisticsRows += `<tr class="odd gradeX">
                                            <td>${result.domain}</td>
                                            <td class="text-center">${result.count}</td>
                                            <td class="text-center">${result.history}</td>
                                        </tr>`;
                    });
                    data.html.items.forEach((result) => {
                        searchRows += `<tr class="odd gradeX">
                                        <td>
                                            <a><b>${result.title}</b></a><br>
                                            <a href="${result.link}" target="_blank">${result.link}</a>
                                        </td>
                                    </tr>`;
                    });
                }
                $('.search-results-for').html(searchKeywords);
                $('#search-result-statistics').html(statisticsRows);
                $('#search-result-items').html(searchRows);
                console.log(data.html);
            }           
        })
        .fail(function(){
            console.log("ajax fail");
        })
        .always(function(){
            //console.log("ajax Complete");
        });        
        return false;
    }    
}


/* $('#searcher_input').bind("enterKey",function(e){ search() }); */
$('#searcher_input').keyup(function(e){
    if(e.keyCode == 13) {
        $(this).trigger("enterKey");
        search();
    }
});
$('#searcher_btn').on('click', function(){
    search();
})