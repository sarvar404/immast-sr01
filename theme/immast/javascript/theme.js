// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * theme.js
 *
 * @package     theme_immast
 * @copyright   2015 LMSACE Dev Team,lmsace.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

(function($) {
    // Keep first-party links inside the local Moodle instance, including links
    // rendered from course and block content stored in the database.
    var hostname = window.location.hostname.toLowerCase();
    var isLocalMoodle = hostname === 'localhost' || hostname === '127.0.0.1' || hostname === '::1';
    if (isLocalMoodle) {
        var moodleBaseUrl = window.M && window.M.cfg && window.M.cfg.wwwroot
            ? window.M.cfg.wwwroot
            : window.location.origin;
        var localHomeUrl = moodleBaseUrl.replace(/\/+$/, '') + '/?redirect=0';

        var localizeFirstPartyLinks = function(root) {
            var links = [];
            if (root.matches && root.matches('a[href]')) {
                links.push(root);
            }
            if (root.querySelectorAll) {
                links = links.concat(Array.prototype.slice.call(root.querySelectorAll('a[href]')));
            }

            links.forEach(function(link) {
                try {
                    var url = new URL(link.getAttribute('href'), document.baseURI);
                    var linkHost = url.hostname.toLowerCase();
                    if (linkHost === 'immast.org' || linkHost.endsWith('.immast.org')) {
                        link.href = localHomeUrl;
                    }
                } catch (e) {
                    // Ignore malformed or non-URL links.
                }
            });
        };

        localizeFirstPartyLinks(document);
        if (window.MutationObserver && document.documentElement) {
            new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(localizeFirstPartyLinks);
                });
            }).observe(document.documentElement, {childList: true, subtree: true});
        }
    }

    var img = $("header#header").find('.avatars').find('img[src$="/u/f2"]');
    var src = img.attr('src');
    img.attr('src', src + '_white');
    var msg = $("header#header").find('#nav-message-popover-container .nav-link').find("img[src$='t/message']");
    var msgsrc = msg.attr('src');
    msg.attr('src', msgsrc + "_white");
    var note = $("header#header").find('#nav-notification-popover-container .nav-link').find("img[src$='i/notifications']");
    var notesrc = note.attr('src');
    note.attr('src', notesrc + "_white");

    /* ------- Check navbar button status -------- */
    if ($("#header .navbar-nav button").attr('aria-expanded') === "true") {
        $("#header .navbar-nav").find('button').addClass('is-active');
    }
    /* ------ Event for change the drawer navbar style  ------ */
    $("#header .navbar-nav button").click(function() {
        var $this = $(this);
        setTimeout(function() {
            if ($this.attr('aria-expanded') === "true") {
                $("#header .navbar-nav").find('button').addClass('is-active');
            } else {
                $("#header .navbar-nav").find('button').removeClass('is-active');
            }
        }, 200);
    });
	
	if (window.location.pathname === "/enrol/index.php"){

	$('.slick-carousel').slick(
	{
		"autoplay" : true
		, "autoplaySpeed" : 2000
		, "accessibility" : true
	});
	
	}

})(jQuery);

