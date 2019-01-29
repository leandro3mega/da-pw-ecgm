var fnc_api;
$(document).ready(function () {
    $('.dropdown-submenu a.test').on("click", function (e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });

    fnc_api = new Funcoes_API();
});

var Funcoes_API = function () {
    var that = this;
    this.minIndex = 0;
    this.maxIndex = 12;
    var cond;
    //Aqui vão ser obtidos todos os projetos mediante a condição
    this.getProjetos = function (filtro) {
        var tabela, tipo, valor = '';
        if (!filtro) {
            $.post("./api/api.php", {
                'condicoes': ' ',
                'minIndex': that.minIndex,
                'maxIndex': that.maxIndex
            }, function (res) {
                that.render(res);
                if (Object.keys(res).length < 12) {
                    $("[data-pagina-cima]").attr("disabled", true);
                } else {
                    $("[data-pagina-cima]").attr("disabled", false);
                }
            }, 'json');
        } else {
            cond = that.getCheckCond();
            $.post("./api/api.php", {
                'condicoes': cond,
                'minIndex': that.minIndex,
                'maxIndex': that.maxIndex
            }, function (res) {
                if (res == '0') {
                    $("[data-grid-projetos]").html('<h1 class="margin-auto">Sem projetos</h1>')
                } else {
                    that.render(res);
                    if (Object.keys(res).length < 12) {
                        $("[data-pagina-cima]").attr("disabled", true);
                    } else {
                        $("[data-pagina-cima]").attr("disabled", false);
                    }
                }
            }, 'json');
        }


    };
    //Aqui vão ser enderizados para o ecrã cada projeto
    this.render = function (res) {
        var html = [];
        for (var i in res) {
            html.push("<div class='col-lg-3 col-md-4 col-xs-6' style='padding-left:0px; padding-right:0px'; margin-top:auto; margin-button:auto;>" +
                "<a href='indexport.php?idprojeto=" + res[i]['idprojeto'] + "'' class='d-block h-100 img-projeto' style='margin-bottom: 0px; background-color:rgb(238, 238, 238); border: 1px solid #fff;'>" +
                "<img class='img-fluid img-thumbnail img-projeto' src='../backoffice/images/projetos/imagens/" + res[i]['img'] + "'>" +
                "</a>" +
                "</div>");

        }
        $("[data-grid-projetos]").html(html.join(' '));
    };
    this.getCheckCond = function () {
        var html = ['WHERE projeto.idprojeto = projeto.idprojeto'];
        $("[data-tipo]").each(function () {
            tabela = $(this).data('tabela');
            tipo = $(this).data('tipo');
            if ($(this).prop('checked') == true) {
                valor = $(this).data('valor');
                if (tipo == 'data') {
                    html.push('INSTR(' + tabela + '.' + tipo + ',' + valor + ') > 0');
                } else {
                    html.push(tabela + '.' + tipo + ' = ' + valor);
                }
            } else if ($(this).data('procura') == 'procura') {
                if ($(this).val().trim() != "") {
                    html.push('INSTR(' + tabela + '.' + tipo + ',\"' + $(this).val().trim() + '\") > 0');
                }
            }
        });
        return html.join(' AND ');
    };
    //Verificar em que estado estão os botões de pesquisa apos realizar click
    this.checkstate = function () {

        if (that.minIndex == 0) {
            $("[data-pagina-baixo]").attr("disabled", true);
        } else {
            $("[data-pagina-baixo]").attr("disabled", false);
        }

    };
    //Aqui são tratatos todos os eventos (selectores/botões/etc)
    this.eventos = function () {
        $("[type='checkbox']").on('click', function () {
            that.minIndex = 0;
            that.maxIndex = 12;
            that.getProjetos(true);
        });
        $("[data-btn-pesquisa]").on('click', function () {
            that.minIndex = 0;
            that.maxIndex = 12;
            that.getProjetos(true);
        });
        $("[data-pagina-cima]").on('click', function () {
            that.minIndex = that.maxIndex;
            that.maxIndex = that.maxIndex + 12;
            that.getProjetos(true);
            that.checkstate();
        });
        $("[data-pagina-baixo]").on('click', function () {
            that.maxIndex = that.minIndex;
            that.minIndex = that.minIndex - 12;
            that.getProjetos(true);
            that.checkstate();
        });
    };
    //Construtor do objeto
    this.construtor = function () {
        that.getProjetos(false);
        that.checkstate();
        that.eventos();
    };
    that.construtor();
}