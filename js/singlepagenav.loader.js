// singlePageNav initialization & configuration
$('ul.navigation').singlePageNav({
    offset: $('#header').outerHeight(),
    filter: ':not(.external)',
    updateHash: true,
    currentClass: 'active'
});
