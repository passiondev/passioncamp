function() {
    App.Assignments = {
        init: function() {
            $(".close-btn").on("click", function() {
                $(e.target).parent().addClass("hide")
            })

            $(".attendee-list-js li").each(function() {
                App.Assignments.makeDraggable(this)
            })

            App.Assignments.makeUnassignDroppable($(".attendee-list-js"))

            $(".area-droppable-js").each(function() {
                App.Assignments.makeDroppable(this)
            })
        },
        makeUnassignDroppable: function(e) {
            return $(e).droppable({
                accept: ".sortable-item-js",
                appendTo: "body",
                helper: "clone",
                drop: App.Assignments.unassignDrop
            })
        },
        makeDraggable: function(e) {
            return $(e).draggable({
                containment: "body",
                appendTo: "body",
                helper: "clone",
                revert: "invalid"
            })
        },
        makeDroppable: function(e) {
            return $(e).droppable({
                accept: ".sortable-item-js",
                appendTo: "body",
                helper: "clone",
                activate: App.Assignments.droppableActivate,
                deactivate: App.Assignments.droppableDeactivate,
                over: App.Assignments.droppableOver,
                drop: App.Assignments.droppableDrop,
                out: App.Assignments.droppableOut
            })
        },
        droppableDeactivate: function(e, t) {
            return App.Assignments.resetDroppable(e.target), $(e.target).removeClass("glowy")
        },
        droppableActivate: function(e, t) {
            return App.Assignments.permitsDrop($(e.target), t.draggable) ? $(e.target).addClass("glowy") : $(e.target).removeClass("glowy")
        },
        droppableOut: function(e, t) {
            return App.Assignments.resetDroppable(e.target)
        },
        unassignDrop: function(e, t) {
            var n, r, i;
            
            r = t.draggable.data("id");
            n = t.draggable.data("assignment-area-id");
            i = t.draggable.data("event-id");

            r && n && i ? $.ajax({
                url: "/assignment_areas/" + n + "/unassign",
                data: {
                    event_id: i,
                    attendee_ids: [r]
                },
                method: "POST"
            }) : void 0
        },
        droppableDrop: function(e, t) {
            var n, r, i;
            if (App.Assignments.permitsDrop($(e.target), t.draggable)) {
                r = t.draggable.data("id");
                i = $(e.target).data("event-id");
                n = $(e.target).data("area-id");

                App.Assignments.commitDrop(n, i, r) 
            }
        },
        commitDrop: function(e, t, n) {
            return $.ajax({
                url: "/assignment_areas/" + e + "/assign",
                data: {
                    event_id: t,
                    attendee_ids: [n]
                },
                method: "POST"
            })
        },
        resetDroppable: function(e) {
            $(e).removeClass("assignment-type__full");
            $(e).find(".drop-zone").html("");
        },
        droppableOver: function(e, t) {
            var n, r;
            if (App.Assignments.permitsDrop($(e.target), t.draggable)) {
                n = $(e.target).data("area-name")
                $(e.target).addClass("glowy");
                $(e.target).addClass("active-drop");
                $(e.target).find(".drop-zone")
                    .html('<div class="drop-it"><span class="icon icon-simple-person-outline"></span><div class="area-add-text">Add to <span>' + n + "</span></div></div>");
            } else {
                r = App.Assignments.restrictedReason($(e.target), t.draggable);
                $(e.target).addClass("assignment-type__full");
                $(e.target).find(".drop-zone")
                    .html('<div class="no-drop"><span class="icon icon-restricted"></span><div class="reject-reason">' + r + "</div></div>")
            }
        },
        sortableReceive: function(e, t) {
            return App.Assignments.permitsDrop($(e.target), t.item) ? void 0 : t.sender.sortable("cancel")
        },
        genderPermitsDrop: function(e, t) {
            return App.Assignments.hasNoValue(e.data("gender")) || e.data("gender") === t.data("gender")
        },
        gradePermitsDrop: function(e, t) {
            return App.Assignments.hasNoValue(e.data("grade")) || e.data("grade") === t.data("grade")
        },
        spacePermitsDrop: function(e) {
            return App.Assignments.hasNoValue(e.data("full"))
        },
        hasNoValue: function(e) {
            return "undefined" == typeof e || "" === e
        },
        restrictedReason: function(e, t) {
            var n;
            return n = [], App.Assignments.genderPermitsDrop(e, t) || n.push("Wrong Gender"), App.Assignments.gradePermitsDrop(e, t) || n.push("Wrong Grade"), App.Assignments.spacePermitsDrop(e, t) || n.push("Full"), n.join(", ")
        },
        permitsDrop: function(e, t) {
            return App.Assignments.genderPermitsDrop(e, t) && App.Assignments.gradePermitsDrop(e, t) && App.Assignments.spacePermitsDrop(e, t)
        }
    }
}.call(this)