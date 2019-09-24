"use strict"

require('../css/character.scss');

import $ from 'jquery';

$(document).ready(function(){
    $('.gearswitcher').on('click', function() {
        console.log('test')
        $('.gear-overview').hide();
        $(`#gear-${$(this).data('class')}`).show();

        $('.gear-stats').hide();
        $(`#gear-stats-${$(this).data('class')}`).show();
    });

    $('.equip-details').each(function() {
        let that = this;
        let interval = setInterval(function () {
            if ($(that).css('display') === 'block') {
                clearInterval(interval);

                $.post(`https://xivapi.com/item/${$(that).data('id')}?columns=ClassJobCategory.Name,BaseParam0.Name_en,BaseParamValue0,BaseParam1.Name_en,BaseParamValue1,BaseParam2.Name_en,BaseParamValue2,BaseParam3.Name_en,BaseParamValue3,BaseParam4.Name_en,BaseParamValue4,BaseParamValue5.Name_en`, function (res, ok) {
                    if (ok === 'success') {
                        let list = [];
                        // set classjobs
                        $(that).find('.equipimage-classjobs').html(res.ClassJobCategory.Name);

                        // set stats
                        for (let i = 0; i < 6; i++) {
                            if (!res['BaseParam' + i].Name_en)
                                break;

                            list.push({
                                name: res['BaseParam' + i].Name_en,
                                value: res['BaseParamValue' + i]
                            })
                        }

                        let str = '<div class="row mb-2">';
                        list.forEach(el => {
                            str += `<div class="col-6" style="line-height:1.2;"><span class="small"><strong>${el.name}</strong> + ${el.value}</span></div>`;
                        });
                        str += '</div>';
                        $(that).find('.equipimage-stats').html(str);

                        $(that).find('.materia-row').each(function(){
                            let that = this;
                            $.post(`https://xivapi.com/item/${$(this).data('itemid')}?columns=Materia.BaseParam.Name,Materia.Value`, function (res, ok) {
                                if (ok === 'success') {
                                    $(`.equipimage-mat-${$(that).data('id')}-stats`).html('- <strong>' + res.Materia.BaseParam.Name + '</strong> +' + res.Materia.Value)
                                }
                            });
                        });
                    }
                })
            }
        }, 200)
    });
});