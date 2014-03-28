/*
 * Utility methos used for UI Theme Selector
 */
function toggleCustomTheme() {
    $('body').toggleClass('custom');
    resizePageLayout();
}
;

function resizePageLayout() {
    var pageLayout = $("body").data("layout");
    if (pageLayout)
        pageLayout.resizeAll();
}
;

/*
 * Define options for all the layouts
 */

var pageLayoutOptions = {
    name: 'pageLayout' // only for debugging
    , resizeWithWindowDelay: 250 // delay calling resizeAll when window is *still* resizing
    //, resizeWithWindowMaxDelay: 2000 // force resize every XX ms while window is being resized
    , resizable: false
    , slidable: false
    , closable: false
    , north__paneSelector: "#outer-north"
    , center__paneSelector: "#outer-center"
    , south__paneSelector: "#outer-south"
    , south__spacing_open: 0
    , north__spacing_open: 0

    // add a child-layout inside the center-pane
    , center__children: {
        name: 'tabsContainerLayout'
        , resizable: false
        , slidable: false
        , closable: false
        , north__paneSelector: "#tabbuttons"
        , center__paneSelector: "#tabpanels"
        , spacing_open: 0
        , center__onresize: $.layout.callbacks.resizeTabLayout // resize ALL visible layouts nested inside
    }
};



// define sidebar options here because are used for BOTH east & west tab-panes (see below)
var sidebarLayoutOptions = {
    name: 'sidebarLayout' // only for debugging
    , showErrorMessages: false // some panes do not have an inner layout
    , resizeWhileDragging: true
    , north__size: "30%"
    , south__size: "30%"
    , minSize: 100
    , center__minHeight: 100
    , spacing_open: 10
    , spacing_closed: 10
    , contentSelector: ".ui-widget-content"
    , togglerContent_open: '<div class="ui-icon"></div>'
    , togglerContent_closed: '<div class="ui-icon"></div>'
};

// Analysis Tab west side
var sidebarLayoutOptions2West = {
    name: 'sidebarLayout2' // only for debugging
    , showErrorMessages: false // some panes do not have an inner layout
    , resizeWhileDragging: true
    , north__size: "60%"
    , south__size: "40%"
    , minSize: 100
    , center__minHeight: 100
    , spacing_open: 10
    , spacing_closed: 10
    , contentSelector: ".ui-widget-content"
    , togglerContent_open: '<div class="ui-icon"></div>'
    , togglerContent_closed: '<div class="ui-icon"></div>'
};

// Analysis Tab east side
var sidebarLayoutOptions2East = {
    name: 'sidebarLayout2' // only for debugging
    , showErrorMessages: false // some panes do not have an inner layout
    , resizeWhileDragging: true
    , north__size: "30%"
    , south__size: "60%"
    , minSize: 100
    , center__minHeight: 100
    , spacing_open: 10
    , spacing_closed: 10
    , contentSelector: ".ui-widget-content"
    , togglerContent_open: '<div class="ui-icon"></div>'
    , togglerContent_closed: '<div class="ui-icon"></div>'
};


var sidebarLayoutOptions3 = {
    name: 'sidebarLayout3' // only for debugging
    , showErrorMessages: false // some panes do not have an inner layout
    , resizeWhileDragging: true
    , north__size: "30%"
    , south__size: "30%"
    , minSize: 0
    , center__minHeight: 0
    , spacing_open: 10
    , spacing_closed: 10
    , contentSelector: ".ui-widget-content"
    , togglerContent_open: '<div class="ui-icon"></div>'
    , togglerContent_closed: '<div class="ui-icon"></div>'
};

// options used for the tab-panel-layout on all 3 tabs
var tabLayoutOptions = {
    // name: 'tabPanelLayout' // only for debugging
    resizeWithWindow: false // required because layout is 'nested' inside tabpanels container
    //, resizeWhileDragging: true // slow in IE because of the nested layouts
    , resizerDragOpacity: 0.5
    , north__resizable: false
    , south__resizable: false
    , north__closable: false
    , south__closable: false
    , west__minSize: 150
    , east__minSize: 150
    , center__minWidth: 200
    , spacing_open: 10
    , spacing_closed: 10
    , contentSelector: ".ui-widget-content"
    , togglerContent_open: '<div class="ui-icon"></div>'
    , togglerContent_closed: '<div class="ui-icon"></div>'
    , triggerEventsOnLoad: true // so center__onresize is triggered when layout inits
    , center__onresize: $.layout.callbacks.resizePaneAccordions // resize ALL Accordions nested inside
    , west__onresize: $.layout.callbacks.resizePaneAccordions // ditto for west-pane
    , west__children: sidebarLayoutOptions
    , east__children: sidebarLayoutOptions
};


var tabLayoutOptions2 = {
    // name: 'tabPanelLayout' // only for debugging
    resizeWithWindow: true // required because layout is 'nested' inside tabpanels container
    //, resizeWhileDragging: true // slow in IE because of the nested layouts
    , resizerDragOpacity: 0.5
    , north__resizable: false
    , south__resizable: true
    , north__closable: false
    , south__closable: true
    , west__minSize: 200
    , east__minSize: 450
    , center__minWidth: 100
    , spacing_open: 10
    , spacing_closed: 10
    , contentSelector: ".ui-widget-content"
    , togglerContent_open: '<div class="ui-icon"></div>'
    , togglerContent_closed: '<div class="ui-icon"></div>'
    , triggerEventsOnLoad: true // so center__onresize is triggered when layout inits
    , center__onresize: $.layout.callbacks.resizePaneAccordions // resize ALL Accordions nested inside
    , west__onresize: $.layout.callbacks.resizePaneAccordions // ditto for west-pane
    , west__children: sidebarLayoutOptions2West
    , east__children: sidebarLayoutOptions2East
};




var tabLayoutOptions3 = {
    // name: 'tabPanelLayout' // only for debugging
    resizeWithWindow: false // required because layout is 'nested' inside tabpanels container
    //, resizeWhileDragging: true // slow in IE because of the nested layouts
    , resizerDragOpacity: 0.5
    , north__resizable: false
    , south__resizable: false
    , north__closable: false
    , south__closable: false
    , west__minSize: 0
    , east__minSize: 0
    , center__minWidth: 400
    , spacing_open: 10
    , spacing_closed: 10
    , contentSelector: ".ui-widget-content"
    , togglerContent_open: '<div class="ui-icon"></div>'
    , togglerContent_closed: '<div class="ui-icon"></div>'
    , triggerEventsOnLoad: true // so center__onresize is triggered when layout inits
    , center__onresize: $.layout.callbacks.resizePaneAccordions // resize ALL Accordions nested inside
    , west__onresize: $.layout.callbacks.resizePaneAccordions // ditto for west-pane
    , west__children: sidebarLayoutOptions3
    , east__children: sidebarLayoutOptions3
};


$(document).ready(function() {

    // create the page-layout, which will ALSO create the tabs-wrapper child-layout
    var pageLayout = $("body").layout(pageLayoutOptions);

    // init the tabs inside the center-pane
    // NOTE: layout.center = NEW pane-instance object
    pageLayout.center.pane
            .tabs({
                // using callback addon
                activate: $.layout.callbacks.resizeTabLayout

                        /* OR using a manual/custom callback
                         activate: function (evt, ui) {
                         var tabLayout = $(ui.newPanel).data("layout");
                         if ( tabLayout ) tabLayout.resizeAll();
                         }*/
            })
            // make the tabs sortable
            .find(".ui-tabs-nav").sortable({axis: 'x', zIndex: 2}).end()
            ;
    // after creating the tabs, resize the tabs-wrapper layout...
    // we can access this layout as a 'child' property of the outer-center pane
    pageLayout.center.children.tabsContainerLayout.resizeAll();

    // init ALL the tab-layouts - all use the same options
    // layout-initialization will _complete_ the first time each layout becomes 'visible'
    $("#tab1").layout(tabLayoutOptions);
    $("#tab2").layout(tabLayoutOptions2);
    $("#tab3").layout(tabLayoutOptions3);

    // init inner-tabs inside outer-tab #3
    $("#innerTabs").tabs({
        // look for and resize inner-accordion(s) each time a tab-panel is shown
        //activate: $.layout.callbacks.resizePaneAccordions
    });
    
    $("#innerTabs2").tabs({
        // look for and resize inner-accordion(s) each time a tab-panel is shown
        //activate: $.layout.callbacks.resizePaneAccordions
    });    

    // init ALL accordions (all have .accordion class assigned)
    // accordions' 'height' will be reset as each becomes 'visible'
    $(".accordion").accordion({heightStyle: "fill"});

    /* UI pseudo-classes allow all UI elements to be easily found...
     alert( 'Number of Accordion widgets = '+ $(":ui-accordion").length );
     alert( 'Number of Tabs widgets = '+ $(":ui-tabs").length );
     */

    //addThemeSwitcher('#outer-north', {top: '13px', right: '20px'});
    // if a theme is applied by ThemeSwitch *onLoad*, it may change the height of some content,
    // so we need to call resizeLayout to 'correct' any header/footer heights affected
    // call multiple times so fast browsers update quickly, and slower ones eventually!
    // NOTE: this is only necessary because we are changing CSS *AFTER LOADING* (eg: themeSwitcher)
    //setTimeout( resizePageLayout, 1000 ); /* allow time for browser to re-render for theme */
    //setTimeout( resizePageLayout, 5000 ); /* for really slow browsers */
});
