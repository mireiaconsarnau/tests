void function(exports,$,_,Backbone){Backbone.UndoManager.removeUndoType("add");Backbone.UndoManager.removeUndoType("remove");_(exports).extend({onbeforeunload:function(){},slider:new JSNES_SliderView({el:".jsn-es-app",saveTarget:"#jform_slider_data"})});$(".dropdown-toggle").dropdown()}(this,JSNES_jQuery,JSNES_Underscore,JSNES_Backbone);