//les options de la fenetre
// 2005.01.12 JLT correction param. taille fen�tre pour les activit�s flash 
// de 800x570 � 800x593
var toolsWindowOptions = 
	"toolbar=no"
	+",location=no"
	+",directories=no"
	+",status=no" 
	+",menubar=no"
	+",scrollbars=yes"
	+",resizable=yes" 
	+",width=800"
	+",height=593";

var toolsWindow;

function OpenToolsWindow(theFile) {
	if ((toolsWindow == null) || (toolsWindow.closed)){
		toolsWindow = this.open(theFile, "toolsWindow", toolsWindowOptions);
	} 
	else {
		toolsWindow.location.replace(theFile);
	}
	if (window.focus)
		toolsWindow.focus(); 

}
