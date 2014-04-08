CKEDITOR.dialog.add( 'carrosselDialog', function ( editor ) {
    var carrosselData = [];
    var carrosselDropZone = null;
    return {
        title: 'Carrossel Properties',
        minWidth: 400,
        minHeight: 200,
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'html',
                        html: '<div id="dropzone" class="dropzone"></div>'
                    }
                ]
            },
            {
                id: 'tab-adv',
                label: 'Advanced Settings',
                elements: [
                    // UI elements of the second tab will be defined here
                ]
            }
        ],
        onLoad: function(){
            carrosselDropZone = new Dropzone("div#dropzone", {
                url: window.lineStormTags.mediaBank.upload,
                acceptedFiles: 'image/*',
                init: function(){
                    this.on("success", function(file, response) {
                        carrosselData.push(response);
                        console.log(file);
                        console.log(response);
                        alert("Added file.");
                    });
                    this.on("error", function(file, response) {
                        this.removeFile(file);
                        alert("Cannot add file: "+response);
                    });
                },
                previewTemplate:    '   <div class="dz-preview">' +
                                    '       <img class="dz-image" data-dz-thumbnail />' +
                                    '       <div class="dz-details">' +
                                    '           <div class="row">' +
                                    '               <div class="col-xs-12">' +
                                    '                   <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>' +
                                    '               </div>' +
                                    '           </div>' +
                                    '           <div class="row">' +
                                    '               <div class="col-xs-4">' +
                                    '                   <strong>Details:</strong>' +
                                    '               </div>' +
                                    '               <div class="col-xs-8">' +
                                    '                   <div class="dz-size" data-dz-size></div>' +
                                    '               </div>' +
                                    '           </div>' +
                                    '           <div class="row">' +
                                    '               <div class="col-xs-4">' +
                                    '                   <strong>Caption:</strong>' +
                                    '               </div>' +
                                    '               <div class="col-xs-8">' +
                                    '                   <textarea class="form-control"></textarea>' +
                                    '               </div>' +
                                    '           </div>' +
                                    '       </div>' +
                                    '   </div>'
            });
        },
        onOk: function(){
            carrosselDropZone.removeAllFiles();
            console.log(carrosselData);
            carrosselData = [];
        },
        onCancel: function(){
            carrosselDropZone.removeAllFiles();
            console.log(carrosselData);
            carrosselData = [];
        }
    };
});
