function sendBarcode(barcode, barcodeType, animation, scanType, func, funcParams) { 
    if(barcodeType == undefined)
    {
        barcodeType = "";
    }
    if(animation == undefined)
    {
        animation = true;
    }

    $.ajax({
        url: 'process-barcode',
        type: 'POST',
        dataType: "json",
        data: { barcode: barcode , barcodeType: barcodeType, animation: animation, scanType: scanType},
        success: function(response) {
            console.log(response);

            if(response.status == "ok")
            {
                if(response.hasOwnProperty('redirect'))
                {
                    if(response.withAnimation == "false")
                    {
                        //redirect without animation
                        redirect(response.redirect);
                    }
                    else
                    {
                        //redirect with success animation
                        startSuccessAnim(response.redirect);
                    }
                }


                //functions
                if(func != undefined)
                {
                    if(func == "gegenstandZuLeihgabeHinzufuegen")
                    {
                        gegenstandZuLeihgabeHinzufuegen(funcParams[0], funcParams[1], funcParams[2], funcParams[3], funcParams[4]);
                    }
                    else if(func == "gegenstandRegistrieren")
                    {
                        gegenstandRegistrieren(funcParams[0]);
                    }
                    else if(func == "gegenstandBarcodeBearbeiten")
                    {
                        gegenstandBarcodeBearbeiten(funcParams[0], funcParams[1]);
                    }
                    else if(func == "gegenstandZurueckgeben")
                    {
                        gegenstandZurueckgeben(funcParams[0]);
                    }
                    else if(func == "schuelerausweisBearbeiten")
                    {
                        schuelerausweisBearbeiten(funcParams[0], funcParams[1]);
                    }
                }
            }
            else if(response.status == "error")
            {
                if(response.withAnimation == "false")
                {
                    if(response.hasOwnProperty('redirect'))
                    {
                        //redirect without animation
                        redirect(response.redirect);
                    }
                }
                else
                {
                    if(response.hasOwnProperty('links'))
                    {
                        //error animation with message and links
                        startErrorAnim(response.error_message, response.links);
                    }
                    else
                    {
                        //error animation with message but no links
                        startErrorAnim(response.error_message, []);
                    }
                }
            }
        },
    });
}




function gegenstandZuLeihgabeHinzufuegen(schuelerId, gegenstandId, weitere, lehrer, datumEnde) {
    $.ajax({
        url: 'gegenstandZuLeihgabeHinzufuegen',
        type: 'POST',
        dataType: "json",
        data: { schuelerId: schuelerId, gegenstandId: gegenstandId, weitere: weitere, lehrer: lehrer, datumEnde: datumEnde},
        success: function(response) {
            console.log(response);
        },
    });
}

function gegenstandRegistrieren(gegenstandId) {
    $.ajax({
        url: 'gegenstandRegistrieren',
        type: 'POST',
        dataType: "json",
        data: { gegenstandId: gegenstandId },
        success: function(response) {
            console.log(response);
        },
    });
}

function gegenstandBarcodeBearbeiten(gegenstandId, newId) {
    $.ajax({
        url: 'gegenstandBarcodeBearbeiten',
        type: 'POST',
        dataType: "json",
        data: { gegenstandId: gegenstandId, newId: newId},
        success: function(response) {
            console.log(response);
        },
    });
}

function gegenstandZurueckgeben(gegenstandId) {
    $.ajax({
        url: 'gegenstandZurueckgeben',
        type: 'POST',
        dataType: "json",
        data: { gegenstandId: gegenstandId },
        success: function(response) {
            console.log(response);
        },
    });
}

function schuelerausweisBearbeiten(schuelerId, newId) {
    $.ajax({
        url: 'schuelerausweisBearbeiten',
        type: 'POST',
        dataType: "json",
        data: { schuelerId: schuelerId, newId: newId },
        success: function(response) {
            console.log(response);
        },
    });
}