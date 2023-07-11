/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    var tour = new Tour({
        smartPlacement: false, // does NOT work every time
        backdrop: true,
        template: "<div style='max-width: 614px;' class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content' style='height:200px; overflow:scroll;'></div><div class='popover-navigation'><a class='badge primaryColor' data-role='prev'>« Prev</a><span data-role='separator'></span><a class='badge green' data-role='next'>Next »</a><a class='badge' data-role='end'>Skip</a></div></div>",
        steps: [{
                element: "#tour1",
                title: "Colored pannel",
                placement: "bottom",
                content: "First let's enrol to an exam <img src='images/bsa.png'/>asdfasdfasdfas dfha skdfhkashdfjk haskdfhkl ashdfklj haslkdfhklashdflkashdfkasdf fashdfkhskadf sdafsakdfhsd fhklsadfhklsa"
            }, {
                element: "#tour2",
                title: "Exam Enrolment",
                content: "First let's enrol to an exam"
            }, {
                element: "#tour3",
                title: "Exam Enrolment",
                content: "First let's enrol to an exam"
            }, {
                element: "#tour4",
                title: "Exam Enrolment",
                placement:"top",
                content: "First let's enrol to an exam"
            }, {
                element: "#tour5",
                title: "Exam Enrolment",
                content: "First let's enrol to an exam"
            }, {
                element: "#tour6",
                title: "Exam Enrolment",
                content: "First let's enrol to an exam"
            }]
    });
    $("#startTour").click(function () {
        tour.init();
        tour.restart();
        tour.start(true);
        console.log(tour);

    });
});