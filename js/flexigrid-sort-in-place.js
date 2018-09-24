Index: flexigrid.js
===================================================================
--- flexigrid.js	(revision 3)
+++ flexigrid.js	(working copy)
@@ -49,6 +49,7 @@
 			 hideOnSubmit: true,
 			 autoload: true,
 			 blockOpacity: 0.5,
+             sortorder: 'asc',
 			 onDragCol: false,
 			 onToggleCol: false,
 			 onChangeSort: false,
@@ -553,10 +554,42 @@
 				
 				if (p.onChangeSort)
 					p.onChangeSort(p.sortname,p.sortorder);
-				else
+				else if (!p.url)
+					this.inPlaceSort();
+                else
 					this.populate();				
 			
 			},
+            inPlaceSort: function() {
+				if (!p.sortorder) sortorder="asc";
+			    
+			    col = $(this.hDiv).find("th").index($(this.hDiv).find("th[abbr='"+p.sortname+"']"));
+			    if (isNaN(col) || col < 0) {
+			        alert("Sorting is somehow not configured properly - couldn't find the header for name '"+sortname+"'");
+			    }
+
+			    var rows = $(this.bDiv).find("tr");
+			    if (!rows || rows.length < 2) {
+			    	return;
+			    }
+			    
+			    var parent = $(rows[0]).parent();
+			    
+			    // Hat tip http://www.onemoretake.com/2009/02/25/sorting-elements-with-jquery/
+			    // This comparator could be a lot more sophisticated.  How about adding 
+			    // a property of the column model that says if the data is numeric or textual,
+			    // then sorting based on that?  TODO: yeah.
+			    rows.sort(function(a, b) {
+			        var compA = $(a).find("td:eq("+col+")").text();
+			        var compB = $(b).find("td:eq("+col+")").text();
+			        if (p.sortorder=="asc") return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
+			        else                  return (compA < compB) ? 1 : (compA > compB) ? -1 : 0;
+			    });
+			    
+			    $.each(rows, function() {
+			        $(parent).append(this);
+			    });
+			},
 			buildpager: function(){ //rebuild pager based on new properties
 			
 			$('.pcontrol input',this.pDiv).val(p.page);
