//
let Watching = {keywords : '', page : 1};

const paginators = () => {
    let paginators = 
    `<li class="paginate_button" id="paginate_1">
        <a class="paginate" aria-controls="dataTables-example" data-dt-idx="1" tabindex="0">1</a>
    </li>
    <li class="paginate_button" id="paginate_2">
        <a class="paginate" aria-controls="dataTables-example" data-dt-idx="2" tabindex="0">2</a>
    </li>`;
    return paginators;
}

const search = (page) => {
    if(page == undefined) page = 1;
    //var page = page | 1;

    let keywords = $('#searcher_input').val();
    if(keywords == '' || (Watching.keywords == keywords && Watching.page == page)){

    } else {
        $('.search-results-for').html('<i class="fa fa-spin fa-spinner"></i>');
        $('#search-result-statistics').html('<i class="fa fa-spin fa-spinner"></i>');
        $('#search-result-statistics').html('<i class="fa fa-spin fa-spinner"></i>');
        $('.pagination').html('<i class="fa fa-spin fa-spinner"></i>');
        //
        var data = {
            keywords : keywords,
            page : page,
            action: "search"
        };
        data = $(this).serialize() + "&" + $.param(data);
        $.ajax({
            url: "Controller/Search.php",
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
                //
                $('.search-results-for').html(searchKeywords);
                $('#search-result-statistics').html(statisticsRows);
                $('#search-result-items').html(searchRows);
                //
                Watching.keywords = keywords;
                
                Watching.page = page;
                $('.pagination').html(paginators());
                $('#paginate_' + page).addClass('active');                
                //console.log(data.html);
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

$(document).on('click', '.paginate', function(){
    var page = $(this).attr('data-dt-idx');
    search(page);
})