function ccmLayout(areaNameNumber, cvalID, layout_id, area, locked) {
	this.layout_id = layout_id, this.cvalID = cvalID, this.locked = locked, this.area = area, this.areaNameNumber = areaNameNumber, this.init = function() {
		var a = this;
		this.layoutWrapper = $("#ccm-layout-wrapper-" + this.cvalID), this.ccmControls = this.layoutWrapper.find("#ccm-layout-controls-" + this.cvalID), this.ccmControls.get(0).layoutObj = this, this.ccmControls.mouseover(function() {
			a.dontUpdateTwins = 0, a.highlightAreas(1)
		}), this.ccmControls.mouseout(function() {
			a.moving || a.highlightAreas(0)
		}), this.ccmControls.find(".ccm-layout-menu-button").click(function(b) {
			a.optionsMenu(b)
		}), this.gridSizing()
	}, this.highlightAreas = function(a) {
		var b = this.layoutWrapper.find(".ccm-add-block");
		a ? b.addClass("ccm-layout-area-highlight") : b.removeClass("ccm-layout-area-highlight")
	}, this.optionsMenu = function(a) {
		ccm_hideMenus(), a.stopPropagation(), ccm_menuActivated = !0;
		var b = document.getElementById("ccm-layout-options-menu-" + this.cvalID);
		if (b) b = $("#ccm-layout-options-menu-" + this.cvalID);
		else {
			el = document.createElement("DIV"), el.id = "ccm-layout-options-menu-" + this.cvalID, el.className = "ccm-menu ccm-ui", el.style.display = "none", document.body.appendChild(el), b = $(el), b.css("position", "absolute");
			var c = '<div class="popover"><div class="arrow"></div><div class="inner"><div class="content">';
			c += "<ul>", c += '<li><a onclick="ccm_hideMenus()" class="ccm-menu-icon ccm-icon-edit-menu" dialog-title="' + ccmi18n.editAreaLayout + '" dialog-modal="false" dialog-width="550" dialog-height="280" dialog-append-buttons="true" id="menuEditLayout' + this.cvalID + '" href="' + CCM_TOOLS_PATH + "/edit_area_popup.php?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(this.area) + "&layoutID=" + this.layout_id + "&cvalID=" + this.cvalID + '&atask=layout">' + ccmi18n.editAreaLayout + "</a></li>", c += '<li><a onclick="ccm_hideMenus()" href="javascript:void(0)" class="ccm-menu-icon ccm-icon-move-up" id="menuAreaLayoutMoveUp' + this.cvalID + '">' + ccmi18n.moveLayoutUp + "</a></li>", c += '<li><a onclick="ccm_hideMenus()" href="javascript:void(0)" class="ccm-menu-icon ccm-icon-move-down" id="menuAreaLayoutMoveDown' + this.cvalID + '">' + ccmi18n.moveLayoutDown + "</a></li>";
			var d = this.locked ? ccmi18n.unlockAreaLayout : ccmi18n.lockAreaLayout;
			c += '<li><a onclick="ccm_hideMenus()" href="javascript:void(0)" class="ccm-menu-icon ccm-icon-lock-menu" id="menuAreaLayoutLock' + this.cvalID + '">' + d + "</a></li>", c += '<li><a onclick="ccm_hideMenus()" href="javascript:void(0)" class="ccm-menu-icon ccm-icon-delete-menu" dialog-append-buttons="true" id="menuAreaLayoutDelete' + this.cvalID + '">' + ccmi18n.deleteLayout + "</a></li>", c += "</ul>", c += "</div></div></div>", b.append(c);
			var e = $(b),
				f = this;
			e.find("#menuEditLayout" + this.cvalID).dialog(), e.find("#menuAreaLayoutMoveUp" + this.cvalID).click(function() {
				f.moveLayout("up")
			}), e.find("#menuAreaLayoutMoveDown" + this.cvalID).click(function() {
				f.moveLayout("down")
			}), e.find("#menuAreaLayoutLock" + this.cvalID).click(function() {
				f.lock()
			}), e.find("#menuAreaLayoutDelete" + this.cvalID).click(function() {
				f.deleteLayoutOptions()
			})
		}
		ccm_fadeInMenu(b, a)
	}, this.moveLayout = function(direction) {
		this.moving = 1, ccm_hideHighlighter(), this.highlightAreas(1), this.servicesAjax = $.ajax({
			url: CCM_TOOLS_PATH + "/layout_services/?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(this.area) + "&layoutID=" + this.layout_id + "&cvalID=" + this.cvalID + "&task=move&direction=" + direction + "&areaNameNumber=" + this.areaNameNumber,
			success: function(response) {
				eval("var jObj=" + response), 1 != parseInt(jObj.success) ? alert(jObj.msg) : ccm_mainNavDisableDirectExit()
			}
		});
		var el = $("#ccm-layout-wrapper-" + this.cvalID),
			layoutObj = this;
		if ("down" == direction) {
			var nextLayout = el.next();
			if (nextLayout.hasClass("ccm-layout-wrapper")) return void el.slideUp(600, function() {
				el.insertAfter(nextLayout), el.slideDown(600, function() {
					layoutObj.highlightAreas(0), layoutObj.moving = 0
				})
			});
			ccmAlert.hud(ccmi18n.moveLayoutAtBoundary, 4e3, "icon_move_down", ccmi18n.moveLayoutDown)
		} else if ("up" == direction) {
			var previousLayout = el.prev();
			if (previousLayout.hasClass("ccm-layout-wrapper")) return void el.slideUp(600, function() {
				el.insertBefore(previousLayout), el.slideDown(600, function() {
					layoutObj.highlightAreas(0), layoutObj.moving = 0
				})
			});
			ccmAlert.hud(ccmi18n.moveLayoutAtBoundary, 4e3, "icon_move_up", ccmi18n.moveLayoutUp)
		}
	}, this.lock = function(lock, twinLock) {
		var a = $("#menuAreaLayoutLock" + this.cvalID);
		this.locked = !this.locked, this.locked ? (a.html(ccmi18n.unlockAreaLayout), this.s && this.s.slider("disable")) : (a.find("span").html(ccmi18n.lockAreaLayout), this.s && this.s.slider("enable"));
		var lock = this.locked ? 1 : 0;
		if (!twinLock) {
			this.servicesAjax = $.ajax({
				url: CCM_TOOLS_PATH + "/layout_services/?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(this.area) + "&layoutID=" + this.layout_id + "&task=lock&lock=" + lock,
				success: function(response) {
					eval("var jObj=" + response), 1 != parseInt(jObj.success) && alert(jObj.msg)
				}
			}), this.getTwins();
			for (var i = 0; i < this.layoutTwinObjs.length; i++) this.layoutTwinObjs[i].lock(lock, 1)
		}
	}, this.hasBeenQuickSaved = 0, this.quickSaveLayoutId = 0, this.quickSave = function() {
		var breakPoints = this.ccmControls.find("#layout_col_break_points_" + this.cvalID).val().replace(/%/g, "");
		if (clearTimeout(this.secondSavePauseTmr), !this.hasBeenQuickSaved && this.quickSaveInProgress) return quickSaveLayoutObj = this, void(this.secondSavePauseTmr = setTimeout("quickSaveLayoutObj.quickSave()", 100));
		this.quickSaveInProgress = 1;
		var layoutObj = this,
			modifyLayoutId = this.quickSaveLayoutId ? this.quickSaveLayoutId : this.layout_id;
		this.quickSaveAjax = $.ajax({
			url: CCM_TOOLS_PATH + "/layout_services/?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(this.area) + "&layoutID=" + modifyLayoutId + "&task=quicksave&breakpoints=" + encodeURIComponent(breakPoints),
			success: function(response) {
				eval("var jObj=" + response), 1 != parseInt(jObj.success) ? alert(jObj.msg) : (layoutObj.hasBeenQuickSaved = 1, layoutObj.quickSaveInProgress = 0, jObj.layoutID && (layoutObj.quickSaveLayoutId = jObj.layoutID), ccm_mainNavDisableDirectExit())
			}
		})
	}, this.deleteLayoutOptions = function() {
		var a = 0;
		deleteLayoutObj = this, this.layoutWrapper.find(".ccm-block").each(function(b, c) {
			"none" != c.style.display && (a = 1)
		});
		var b = a ? "135px" : "70px";
		$.fn.dialog.open({
			title: ccmi18n.deleteLayoutOptsTitle,
			href: CCM_TOOLS_PATH + "/layout_services/?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(this.area) + "&layoutID=" + this.layout_id + "&task=deleteOpts&hasBlocks=" + a,
			width: "340px",
			modal: !1,
			appendButtons: !0,
			height: b
		})
	}, this.deleteLayout = function(deleteBlocks) {
		ccm_hideMenus(), jQuery.fn.dialog.closeTop(), this.layoutWrapper.slideUp(300), jQuery.fn.dialog.showLoader();
		var cvalID = this.cvalID;
		this.servicesAjax = $.ajax({
			url: CCM_TOOLS_PATH + "/layout_services/?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(this.area) + "&layoutID=" + this.layout_id + "&task=delete&deleteBlocks=" + parseInt(deleteBlocks) + "&areaNameNumber=" + this.areaNameNumber,
			success: function(response) {
				eval("var jObj=" + response), 1 != parseInt(jObj.success) ? (alert(jObj.msg), jQuery.fn.dialog.hideLoader()) : ($("#ccm-layout-wrapper-" + cvalID).remove(), ccm_hideHighlighter(), ccm_mainNavDisableDirectExit(), jObj.refreshPage ? window.location = window.location : jQuery.fn.dialog.hideLoader())
			}
		})
	}, this.gridSizing = function() {
		this.ccmGrid = $("#ccm-layout-" + this.layout_id);
		var a = parseInt(this.ccmControls.find(".layout_column_count").val());
		if (a > 1) {
			var b = this.ccmControls.find("#layout_col_break_points_" + this.cvalID).val().replace(/%/g, "").split("|");
			this.s = this.ccmControls.find(".ccm-layout-controls-slider"), this.s.get(0).layoutObj = this, this.s.get(0).ccmGrid = this.ccmGrid, this.s.slider({
				step: 1,
				values: b,
				change: function() {
					if (!this.layoutObj.dontUpdateTwins) {
						this.layoutObj.resizeGrid(this.childNodes);
						for (var a = [], b = 0; b < this.childNodes.length; b++) a.push(parseFloat(this.childNodes[b].style.left.replace("%", "")));
						a.sort(function(a, b) {
							return a - b
						}), this.layoutObj.ccmControls.find(".layout_col_break_points").val(a.join("%|") + "%"), this.layoutObj.quickSave(), ccm_arrangeMode = 0, this.layoutObj.moving = 0, this.layoutObj.highlightAreas(0)
					}
				},
				slide: function() {
					ccm_arrangeMode = 1, this.layoutObj.moving = 1, this.layoutObj.dontUpdateTwins || this.layoutObj.resizeGrid(this.childNodes)
				}
			}), parseInt(this.ccmControls.find(".layout_locked").val()) && this.s.slider("disable")
		}
	}, this.getTwins = function() {
		if (!this.layoutTwins) {
			this.layoutTwins = $(".ccm-layout-controls-layoutID-" + this.layout_id).not(this.ccmControls), this.layoutTwinObjs = [];
			for (var a = 0; a < this.layoutTwins.length; a++) this.layoutTwinObjs.push(this.layoutTwins[a].layoutObj), this.layoutTwins[a].handles = $(this.layoutTwins[a]).find(".ui-slider-handle")
		}
		return this.layoutTwins
	}, this.resizeGrid = function(a) {
		var b = [];
		this.getTwins();
		for (var c = 0; c < a.length; c++) {
			var d = parseFloat(a[c].style.left.replace("%", ""));
			if (b.push(d), !this.dontUpdateTwins) for (var e = 0; e < this.layoutTwinObjs.length; e++) this.layoutTwinObjs[e].dontUpdateTwins = 1, this.layoutTwinObjs[e].s.slider("values", c, d)
		}
		b.sort(function(a, b) {
			return a - b
		});
		var f, g = 0;
		for (f = 0; f < b.length; f++) {
			var d = b[f],
				e = d - g;
			if (g += e, $(".ccm-layout-" + this.layout_id + "-col-" + (f + 1)).css("width", e + "%"), !this.dontUpdateTwins) for (j = 0; j < this.layoutTwins.length; j++) this.layoutTwins[j].handles[f].style.left = d + "%"
		}
		$(".ccm-layout-" + this.layout_id + "-col-" + (f + 1)).css("width", 100 - g + "%")
	}
}
function fixResortingDroppables() {
	if (0 == tr_reorderMode) return !1;
	for (var a = $(".dropzone"), b = 0; b < a.length; b++) {
		var c = $(a[b]).attr("id").substr(7);
		c.indexOf("-sub") > 0 && (c = c.substr(0, c.length - 4)), addResortDroppable(c)
	}
}
function addResortDroppable(a) {
	$(".tree-branch" + a).length <= 1 || $("div.tree-dz" + a).droppable({
		accept: ".tree-branch" + a,
		activeClass: "dropzone-ready",
		hoverClass: "dropzone-active",
		drop: function(a, b) {
			var c = b.draggable;
			$(c).insertAfter(this);
			var d = $(c).attr("id").substring(9);
			$("#tree-dz" + d).insertAfter($(c)), rescanDisplayOrder($(this).attr("tree-parent"))
		}
	})
}
function ccm_previewInternalTheme(a, b, c) {
	var d = $("input[name=ctID]").val();
	$.fn.dialog.open({
		title: c,
		href: CCM_TOOLS_PATH + "/themes/preview?themeID=" + b + "&previewCID=" + a + "&ctID=" + d,
		width: "85%",
		modal: !1,
		height: "75%"
	})
}
function ccm_previewMarketplaceTheme(a, b, c, d) {
	var e = $("input[name=ctID]").val();
	$.fn.dialog.open({
		title: c,
		href: CCM_TOOLS_PATH + "/themes/preview?themeCID=" + b + "&previewCID=" + a + "&themeHandle=" + encodeURIComponent(d) + "&ctID=" + e,
		width: "85%",
		modal: !1,
		height: "75%"
	})
}
function ccm_previewComposerDraft(a, b) {
	$.fn.dialog.open({
		title: b,
		href: CCM_TOOLS_PATH + "/composer/preview_frame?previewCID=" + a,
		width: "85%",
		modal: !1,
		height: "75%"
	})
}!
function(a) {
	var b = function() {
			var b = 65,
				c = '<div class="colorpicker"><div class="colorpicker_color"><div><div></div></div></div><div class="colorpicker_hue"><div></div></div><div class="colorpicker_new_color"></div><div class="colorpicker_current_color"></div><div class="colorpicker_hex"><input type="text" class="text" maxlength="6" size="6" /></div><div class="colorpicker_rgb_r colorpicker_field"><input type="text" class="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_g colorpicker_field"><input type="text" class="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_b colorpicker_field"><input type="text" class="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_h colorpicker_field"><input type="text" class="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_s colorpicker_field"><input type="text" class="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_b colorpicker_field"><input type="text" class="text" maxlength="3" size="3" /><span></span></div><input type="button" class="colorpicker_none" name="none" value="x" /><input type="button" class="colorpicker_submit" name="save" value="Ok" /></div>',
				d = {
					eventName: "click",
					onShow: function() {},
					onBeforeShow: function() {},
					onHide: function() {},
					onNone: function() {},
					onChange: function() {},
					onSubmit: function() {},
					color: "ff0000",
					livePreview: !0,
					flat: !1
				},
				e = function(b, c) {
					var d = K(b);
					a(c).data("colorpicker").fields.eq(1).val(d.r).end().eq(2).val(d.g).end().eq(3).val(d.b).end()
				},
				f = function(b, c) {
					a(c).data("colorpicker").fields.eq(4).val(b.h).end().eq(5).val(b.s).end().eq(6).val(b.b).end()
				},
				g = function(b, c) {
					a(c).data("colorpicker").fields.eq(0).val(M(b)).end()
				},
				h = function(b, c) {
					a(c).data("colorpicker").selector.css("backgroundColor", "#" + M({
						h: b.h,
						s: 100,
						b: 100
					})), a(c).data("colorpicker").selectorIndic.css({
						left: parseInt(150 * b.s / 100, 10),
						top: parseInt(150 * (100 - b.b) / 100, 10)
					})
				},
				i = function(b, c) {
					a(c).data("colorpicker").hue.css("top", parseInt(150 - 150 * b.h / 360, 10))
				},
				j = function(b, c) {
					a(c).data("colorpicker").currentColor.css("backgroundColor", "#" + M(b))
				},
				k = function(b, c) {
					a(c).data("colorpicker").newColor.css("backgroundColor", "#" + M(b))
				},
				l = function(c) {
					var d = c.charCode || c.keyCode || -1;
					if (d > b && 90 >= d || 32 == d) return !1;
					var e = a(this).parent().parent();
					e.data("colorpicker").livePreview === !0 && m.apply(this)
				},
				m = function(b) {
					var c, d = a(this).parent().parent();
					d.data("colorpicker") && d.data("colorpicker").fields && (d.data("colorpicker").color = c = this.parentNode.className.indexOf("_hex") > 0 ? I(G(this.value)) : this.parentNode.className.indexOf("_hsb") > 0 ? E({
						h: parseInt(d.data("colorpicker").fields.eq(4).val(), 10),
						s: parseInt(d.data("colorpicker").fields.eq(5).val(), 10),
						b: parseInt(d.data("colorpicker").fields.eq(6).val(), 10)
					}) : J(F({
						r: parseInt(d.data("colorpicker").fields.eq(1).val(), 10),
						g: parseInt(d.data("colorpicker").fields.eq(2).val(), 10),
						b: parseInt(d.data("colorpicker").fields.eq(3).val(), 10)
					})), b && (e(c, d.get(0)), g(c, d.get(0)), f(c, d.get(0))), h(c, d.get(0)), i(c, d.get(0)), k(c, d.get(0)), d.data("colorpicker").onChange.apply(d, [c, M(c), K(c)]))
				},
				n = function() {
					var b = a(this).parent().parent(),
						c = b.data("colorpicker");
					c && c.fields && c.fields.parent().removeClass("colorpicker_focus")
				},
				o = function() {
					b = this.parentNode.className.indexOf("_hex") > 0 ? 70 : 65;
					var c = a(this).parent().parent().data("colorpicker");
					c && c.fields && c.fields.parent().removeClass("colorpicker_focus"), a(this).parent().addClass("colorpicker_focus")
				},
				p = function(b) {
					var c = a(this).parent().find("input").focus(),
						d = {
							el: a(this).parent().addClass("colorpicker_slider"),
							max: this.parentNode.className.indexOf("_hsb_h") > 0 ? 360 : this.parentNode.className.indexOf("_hsb") > 0 ? 100 : 255,
							y: b.pageY,
							field: c,
							val: parseInt(c.val(), 10),
							preview: a(this).parent().parent().data("colorpicker").livePreview
						};
					a(document).bind("mouseup", d, r), a(document).bind("mousemove", d, q)
				},
				q = function(a) {
					return a.data.field.val(Math.max(0, Math.min(a.data.max, parseInt(a.data.val + a.pageY - a.data.y, 10)))), a.data.preview && m.apply(a.data.field.get(0), [!0]), !1
				},
				r = function(b) {
					return m.apply(b.data.field.get(0), [!0]), b.data.el.removeClass("colorpicker_slider").find("input").focus(), a(document).unbind("mouseup", r), a(document).unbind("mousemove", q), !1
				},
				s = function() {
					var b = {
						cal: a(this).parent(),
						y: a(this).offset().top
					};
					b.preview = b.cal.data("colorpicker").livePreview, a(document).bind("mouseup", b, u), a(document).bind("mousemove", b, t)
				},
				t = function(a) {
					return m.apply(a.data.cal.data("colorpicker").fields.eq(4).val(parseInt(360 * (150 - Math.max(0, Math.min(150, a.pageY - a.data.y))) / 150, 10)).get(0), [a.data.preview]), !1
				},
				u = function(b) {
					return e(b.data.cal.data("colorpicker").color, b.data.cal.get(0)), g(b.data.cal.data("colorpicker").color, b.data.cal.get(0)), a(document).unbind("mouseup", u), a(document).unbind("mousemove", t), !1
				},
				v = function() {
					var b = {
						cal: a(this).parent(),
						pos: a(this).offset()
					};
					b.preview = b.cal.data("colorpicker").livePreview, a(document).bind("mouseup", b, x), a(document).bind("mousemove", b, w)
				},
				w = function(a) {
					return m.apply(a.data.cal.data("colorpicker").fields.eq(6).val(parseInt(100 * (150 - Math.max(0, Math.min(150, a.pageY - a.data.pos.top))) / 150, 10)).end().eq(5).val(parseInt(100 * Math.max(0, Math.min(150, a.pageX - a.data.pos.left)) / 150, 10)).get(0), [a.data.preview]), !1
				},
				x = function(b) {
					return e(b.data.cal.data("colorpicker").color, b.data.cal.get(0)), g(b.data.cal.data("colorpicker").color, b.data.cal.get(0)), a(document).unbind("mouseup", x), a(document).unbind("mousemove", w), !1
				},
				y = function() {
					var b = a(this).parent(),
						c = b.data("colorpicker").color;
					b.data("colorpicker").origColor = c, j(c, b.get(0));
					a("#" + a(this).data("colorpickerId"));
					b.data("colorpicker").onSubmit(c, M(c), K(c), b)
				},
				z = function() {
					var b = a(this).parent();
					b.data("colorpicker").onNone(b), b.hide()
				},
				A = function() {
					var b = a("#" + a(this).data("colorpickerId"));
					b.data("colorpicker").onBeforeShow.apply(this, [b.get(0)]);
					var c = a(this).offset(),
						d = D(),
						e = c.top + this.offsetHeight,
						f = c.left;
					return e + 176 > d.t + d.h && (e -= this.offsetHeight + 176), f + 356 > d.l + d.w && (f -= 356), b.css({
						left: f + "px",
						top: e + "px"
					}), 0 != b.data("colorpicker").onShow.apply(this, [b.get(0)]) && b.show(), a(document).bind("mousedown", {
						cal: b
					}, B), !1
				},
				B = function(b) {
					C(b.data.cal.get(0), b.target, b.data.cal.get(0)) || (0 != b.data.cal.data("colorpicker").onHide.apply(this, [b.data.cal.get(0)]) && b.data.cal.hide(), a(document).unbind("mousedown", B))
				},
				C = function(a, b, c) {
					if (a == b) return !0;
					if (a.contains) return a.contains(b);
					if (a.compareDocumentPosition) return !!(16 & a.compareDocumentPosition(b));
					for (var d = b.parentNode; d && d != c;) {
						if (d == a) return !0;
						d = d.parentNode
					}
					return !1
				},
				D = function() {
					var a = "CSS1Compat" == document.compatMode;
					return {
						l: window.pageXOffset || (a ? document.documentElement.scrollLeft : document.body.scrollLeft),
						t: window.pageYOffset || (a ? document.documentElement.scrollTop : document.body.scrollTop),
						w: window.innerWidth || (a ? document.documentElement.clientWidth : document.body.clientWidth),
						h: window.innerHeight || (a ? document.documentElement.clientHeight : document.body.clientHeight)
					}
				},
				E = function(a) {
					return {
						h: Math.min(360, Math.max(0, a.h)),
						s: Math.min(100, Math.max(0, a.s)),
						b: Math.min(100, Math.max(0, a.b))
					}
				},
				F = function(a) {
					return {
						r: Math.min(255, Math.max(0, a.r)),
						g: Math.min(255, Math.max(0, a.g)),
						b: Math.min(255, Math.max(0, a.b))
					}
				},
				G = function(a) {
					var b = 6 - a.length;
					if (b > 0) {
						for (var c = [], d = 0; b > d; d++) c.push("0");
						c.push(a), a = c.join("")
					}
					return a
				},
				H = function(a) {
					var a = parseInt(a.indexOf("#") > -1 ? a.substring(1) : a, 16);
					return {
						r: a >> 16,
						g: (65280 & a) >> 8,
						b: 255 & a
					}
				},
				I = function(a) {
					return J(H(a))
				},
				J = function(a) {
					var b = {};
					return b.b = Math.max(Math.max(a.r, a.g), a.b), b.s = b.b <= 0 ? 0 : Math.round(100 * (b.b - Math.min(Math.min(a.r, a.g), a.b)) / b.b), b.b = Math.round(b.b / 255 * 100), b.h = a.r == a.g && a.g == a.b ? 0 : a.r >= a.g && a.g >= a.b ? 60 * (a.g - a.b) / (a.r - a.b) : a.g >= a.r && a.r >= a.b ? 60 + 60 * (a.g - a.r) / (a.g - a.b) : a.g >= a.b && a.b >= a.r ? 120 + 60 * (a.b - a.r) / (a.g - a.r) : a.b >= a.g && a.g >= a.r ? 180 + 60 * (a.b - a.g) / (a.b - a.r) : a.b >= a.r && a.r >= a.g ? 240 + 60 * (a.r - a.g) / (a.b - a.g) : a.r >= a.b && a.b >= a.g ? 300 + 60 * (a.r - a.b) / (a.r - a.g) : 0, b.h = Math.round(b.h), b
				},
				K = function(a) {
					var b = {},
						c = Math.round(a.h),
						d = Math.round(255 * a.s / 100),
						e = Math.round(255 * a.b / 100);
					if (0 == d) b.r = b.g = b.b = e;
					else {
						var f = e,
							g = (255 - d) * e / 255,
							h = (f - g) * (c % 60) / 60;
						360 == c && (c = 0), 60 > c ? (b.r = f, b.b = g, b.g = g + h) : 120 > c ? (b.g = f, b.b = g, b.r = f - h) : 180 > c ? (b.g = f, b.r = g, b.b = g + h) : 240 > c ? (b.b = f, b.r = g, b.g = f - h) : 300 > c ? (b.b = f, b.g = g, b.r = g + h) : 360 > c ? (b.r = f, b.g = g, b.b = f - h) : (b.r = 0, b.g = 0, b.b = 0)
					}
					return {
						r: Math.round(b.r),
						g: Math.round(b.g),
						b: Math.round(b.b)
					}
				},
				L = function(b) {
					var c = [b.r.toString(16), b.g.toString(16), b.b.toString(16)];
					return a.each(c, function(a, b) {
						1 == b.length && (c[a] = "0" + b)
					}), c.join("")
				},
				M = function(a) {
					return L(K(a))
				};
			return {
				init: function(b) {
					if (b = a.extend({}, d, b || {}), "string" == typeof b.color) b.color = I(b.color);
					else if (void 0 != b.color.r && void 0 != b.color.g && void 0 != b.color.b) b.color = J(b.color);
					else {
						if (void 0 == b.color.h || void 0 == b.color.s || void 0 == b.color.b) return this;
						b.color = E(b.color)
					}
					return b.origColor = b.color, this.each(function() {
						if (!a(this).data("colorpickerId")) {
							var d = "collorpicker_" + parseInt(1e3 * Math.random());
							a(this).data("colorpickerId", d);
							var q = a(c).attr("id", d);
							b.flat ? q.appendTo(this).show() : q.appendTo(document.body), b.fields = q.find("input").bind("keydown", l).bind("change", m).bind("blur", n).bind("focus", o), q.find("span").bind("mousedown", p), b.selector = q.find("div.colorpicker_color").bind("mousedown", v), b.selectorIndic = b.selector.find("div div"), b.hue = q.find("div.colorpicker_hue div"), q.find("div.colorpicker_hue").bind("mousedown", s), b.newColor = q.find("div.colorpicker_new_color"), b.currentColor = q.find("div.colorpicker_current_color"), q.data("colorpicker", b), q.find("input.colorpicker_none").bind("click", z), q.find("input.colorpicker_submit").bind("click", y), e(b.color, q.get(0)), f(b.color, q.get(0)), g(b.color, q.get(0)), i(b.color, q.get(0)), h(b.color, q.get(0)), j(b.color, q.get(0)), k(b.color, q.get(0)), b.flat ? q.css({
								position: "relative",
								display: "block"
							}) : a(this).bind(b.eventName, A)
						}
					})
				},
				showPicker: function() {
					return this.each(function() {
						a(this).data("colorpickerId") && A.apply(this)
					})
				},
				hidePicker: function() {
					return this.each(function() {
						a(this).data("colorpickerId") && a("#" + a(this).data("colorpickerId")).hide()
					})
				},
				setColor: function(b) {
					if ("string" == typeof b) b = I(b);
					else if (void 0 != b.r && void 0 != b.g && void 0 != b.b) b = J(b);
					else {
						if (void 0 == b.h || void 0 == b.s || void 0 == b.b) return this;
						b = E(b)
					}
					return this.each(function() {
						if (a(this).data("colorpickerId")) {
							var c = a("#" + a(this).data("colorpickerId"));
							c.data("colorpicker").color = b, c.data("colorpicker").origColor = b, e(b, c.get(0)), f(b, c.get(0)), g(b, c.get(0)), i(b, c.get(0)), h(b, c.get(0)), j(b, c.get(0)), k(b, c.get(0))
						}
					})
				}
			}
		}();
	a.fn.extend({
		ColorPicker: b.init,
		ColorPickerHide: b.hide,
		ColorPickerShow: b.show,
		ColorPickerSetColor: b.setColor
	})
}(jQuery), function(a) {
	a.fn.hoverIntent = function(b, c) {
		var d = {
			sensitivity: 7,
			interval: 100,
			timeout: 0
		};
		d = a.extend(d, c ? {
			over: b,
			out: c
		} : b);
		var e, f, g, h, i = function(a) {
				e = a.pageX, f = a.pageY
			},
			j = function(b, c) {
				return c.hoverIntent_t = clearTimeout(c.hoverIntent_t), Math.abs(g - e) + Math.abs(h - f) < d.sensitivity ? (a(c).unbind("mousemove", i), c.hoverIntent_s = 1, d.over.apply(c, [b])) : (g = e, h = f, c.hoverIntent_t = setTimeout(function() {
					j(b, c)
				}, d.interval), void 0)
			},
			k = function(a, b) {
				return b.hoverIntent_t = clearTimeout(b.hoverIntent_t), b.hoverIntent_s = 0, d.out.apply(b, [a])
			},
			l = function(b) {
				var c = jQuery.extend({}, b),
					e = this;
				e.hoverIntent_t && (e.hoverIntent_t = clearTimeout(e.hoverIntent_t)), "mouseenter" == b.type ? (g = c.pageX, h = c.pageY, a(e).bind("mousemove", i), 1 != e.hoverIntent_s && (e.hoverIntent_t = setTimeout(function() {
					j(c, e)
				}, d.interval))) : (a(e).unbind("mousemove", i), 1 == e.hoverIntent_s && (e.hoverIntent_t = setTimeout(function() {
					k(c, e)
				}, d.timeout)))
			};
		return this.bind("mouseenter", l).bind("mouseleave", l)
	}
}(jQuery), function(a) {
	var b = null;
	a.fn.liveUpdate = function(b, c) {
		return this.each(function() {
			new a.liveUpdate(this, b, c)
		})
	}, a.liveUpdate = function(b, c, d) {
		this.field = a(b), a(b).data("liveUpdate", this), this.list = a("#" + c), this.lutype = "blocktypes", "undefined" != typeof d && (this.lutype = d), this.list.length > 0 && this.init()
	}, a.liveUpdate.prototype = {
		init: function() {
			var a = this;
			this.setupCache(), this.field.parents("form").submit(function() {
				return !1
			}), this.field.keyup(function() {
				a.filter()
			}), a.filter()
		},
		filter: function() {
			if (this.field.val() != b) {
				if ("" == a.trim(this.field.val())) return void("blocktypes" == this.lutype ? (this.list.children("li").addClass("ccm-block-type-available"), this.list.children("li").removeClass("ccm-block-type-selected")) : "attributes" == this.lutype ? (this.list.children("li").addClass("ccm-attribute-available"), this.list.children("li").removeClass("ccm-attribute-selected")) : "stacks" == this.lutype ? (this.list.children("li").addClass("ccm-stack-available"), this.list.children("li").removeClass("ccm-stack-selected")) : "intelligent-search" == this.lutype ? this.list.is(":visible") && this.list.hide() : this.list.children("li").show());
				"intelligent-search" != this.lutype || this.field.val().length > 2 ? this.displayResults(this.getScores(this.field.val().toLowerCase())) : "intelligent-search" == this.lutype && this.list.is(":visible") && this.list.hide()
			}
			b = this.field.val(), "" == b && "intelligent-search" == this.lutype && this.list.is(":visible") && this.list.hide()
		},
		setupCache: function() {
			var b = this;
			this.cache = [], this.rows = [];
			var c = this.lutype;
			this.list.find("li").each(function() {
				if ("blocktypes" == c) b.cache.push(a(this).find("a.ccm-block-type-inner").html().toLowerCase());
				else if ("attributes" == c) {
					var d = a(this).find("a,span").html().toLowerCase();
					b.cache.push(d)
				} else if ("stacks" == c) {
					var d = a(this).find("a,span").html().toLowerCase();
					b.cache.push(d)
				} else if ("fileset" == c) b.cache.push(a(this).find("label").html().toLowerCase());
				else if ("intelligent-search" == c) {
					var e = a(this).find("span").html();
					e && b.cache.push(e.toLowerCase())
				}
				b.rows.push(a(this))
			}), this.cache_length = this.cache.length
		},
		displayResults: function(b) {
			var c = this;
			if ("blocktypes" == this.lutype) this.list.children("li").removeClass("ccm-block-type-available"), this.list.children("li").removeClass("ccm-block-type-selected"), a.each(b, function(a, b) {
				c.rows[b[1]].addClass("ccm-block-type-available")
			}), a(this.list.find("li.ccm-block-type-available")[0]).addClass("ccm-block-type-selected");
			else if ("attributes" == this.lutype) this.list.children("li").removeClass("ccm-attribute-available"), this.list.children("li").removeClass("ccm-attribute-selected"), this.list.children("li").removeClass("ccm-item-selected"), a.each(b, function(a, b) {
				c.rows[b[1]].addClass("ccm-attribute-available")
			}), this.list.children("li.item-select-list-header").removeClass("ccm-attribute-available"), a(this.list.find("li.ccm-attribute-available")[0]).addClass("ccm-item-selected");
			else if ("stacks" == this.lutype) this.list.children("li").removeClass("ccm-stack-available"), this.list.children("li").removeClass("ccm-stack-selected"), this.list.children("li").removeClass("ccm-item-selected"), a.each(b, function(a, b) {
				c.rows[b[1]].addClass("ccm-stack-available")
			}), this.list.children("li.item-select-list-header").removeClass("ccm-stack-available"), a(this.list.find("li.ccm-stack-available")[0]).addClass("ccm-item-selected");
			else if ("intelligent-search" == this.lutype) {
				this.list.is(":visible") || this.list.fadeIn(160, "easeOutExpo"), this.list.find(".ccm-intelligent-search-results-module-onsite").hide(), this.list.find("li").hide();
				var d = 0;
				a.each(b, function(a, b) {
					$li = c.rows[b[1]], b[0] > .75 && (d++, $li.parent().parent().is(":visible") || $li.parent().parent().show(), $li.show())
				}), this.list.find("li a").removeClass("ccm-intelligent-search-result-selected"), this.list.find("li:visible a:first").addClass("ccm-intelligent-search-result-selected")
			} else this.list.children("li").hide(), a.each(b, function(a, b) {
				c.rows[b[1]].show()
			})
		},
		getScores: function(a) {
			for (var b = [], c = 0; c < this.cache_length; c++) {
				var d = this.cache[c].score(a);
				d > 0 && b.push([d, c])
			}
			return b.sort(function(a, b) {
				return b[0] - a[0]
			})
		}
	}
}(jQuery), function($) {
	$.extend({
		metadata: {
			defaults: {
				type: "class",
				name: "metadata",
				cre: /({.*})/,
				single: "metadata"
			},
			setType: function(a, b) {
				this.defaults.type = a, this.defaults.name = b
			},
			get: function(elem, opts) {
				var settings = $.extend({}, this.defaults, opts);
				settings.single.length || (settings.single = "metadata");
				var data = $.data(elem, settings.single);
				if (data) return data;
				if (data = "{}", "class" == settings.type) {
					var m = settings.cre.exec(elem.className);
					m && (data = m[1])
				} else if ("elem" == settings.type) {
					if (!elem.getElementsByTagName) return;
					var e = elem.getElementsByTagName(settings.name);
					e.length && (data = $.trim(e[0].innerHTML))
				} else if (void 0 != elem.getAttribute) {
					var attr = elem.getAttribute(settings.name);
					attr && (data = attr)
				}
				return data.indexOf("{") < 0 && (data = "{" + data + "}"), data = eval("(" + data + ")"), $.data(elem, settings.single, data), data
			}
		}
	}), $.fn.metadata = function(a) {
		return $.metadata.get(this[0], a)
	}
}(jQuery), function() {
	var a, b, c, d, e = function(a, b) {
			return function() {
				return a.apply(b, arguments)
			}
		};
	d = this, a = jQuery, a.fn.extend({
		chosen: function(c) {
			return "msie" !== a.browser || "6.0" !== a.browser.version && "7.0" !== a.browser.version ? a(this).each(function() {
				return a(this).hasClass("chzn-done") ? void 0 : new b(this, c)
			}) : this
		}
	}), b = function() {
		function b(b, c) {
			this.form_field = b, this.options = null != c ? c : {}, this.set_default_values(), this.form_field_jq = a(this.form_field), this.is_multiple = this.form_field.multiple, this.is_rtl = this.form_field_jq.hasClass("chzn-rtl"), this.default_text_default = this.form_field.multiple ? this.options.placeholder_text_multiple || this.options.placeholder_text || "Select Some Options" : this.options.placeholder_text_single || this.options.placeholder_text || "Select an Option", this.set_up_html(), this.register_observers(), this.form_field_jq.addClass("chzn-done")
		}
		return b.prototype.set_default_values = function() {
			return this.click_test_action = e(function(a) {
				return this.test_active_click(a)
			}, this), this.activate_action = e(function(a) {
				return this.activate_field(a)
			}, this), this.active_field = !1, this.mouse_on_container = !1, this.results_showing = !1, this.result_highlighted = null, this.result_single_selected = null, this.allow_single_deselect = null != this.options.allow_single_deselect && "" === this.form_field.options[0].text ? this.options.allow_single_deselect : !1, this.disable_search_threshold = this.options.disable_search_threshold || 0, this.choices = 0, this.results_none_found = this.options.no_results_text || "No results match"
		}, b.prototype.set_up_html = function() {
			var b, d, e, f;
			return this.container_id = this.form_field.id.length ? this.form_field.id.replace(/(:|\.)/g, "_") : this.generate_field_id(), this.container_id += "_chzn", this.f_width = this.form_field_jq.outerWidth(), this.default_text = this.form_field_jq.data("placeholder") ? this.form_field_jq.data("placeholder") : this.default_text_default, b = a("<div />", {
				id: this.container_id,
				"class": "chzn-container" + (this.is_rtl ? " chzn-rtl" : ""),
				style: "width: " + this.f_width + "px;"
			}), b.html(this.is_multiple ? '<ul class="chzn-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>' : '<a href="javascript:void(0)" class="chzn-single"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>'), this.form_field_jq.hide().after(b), this.container = a("#" + this.container_id), this.container.addClass("chzn-container-" + (this.is_multiple ? "multi" : "single")), !this.is_multiple && this.form_field.options.length <= this.disable_search_threshold && this.container.addClass("chzn-container-single-nosearch"), this.dropdown = this.container.find("div.chzn-drop").first(), d = this.container.height(), e = this.f_width - c(this.dropdown), this.dropdown.css({
				width: e + "px",
				top: d + "px"
			}), this.search_field = this.container.find("input").first(), this.search_results = this.container.find("ul.chzn-results").first(), this.search_field_scale(), this.search_no_results = this.container.find("li.no-results").first(), this.is_multiple ? (this.search_choices = this.container.find("ul.chzn-choices").first(), this.search_container = this.container.find("li.search-field").first()) : (this.search_container = this.container.find("div.chzn-search").first(), this.selected_item = this.container.find(".chzn-single").first(), f = e - c(this.search_container) - c(this.search_field), this.search_field.css({
				width: f + "px"
			})), this.results_build(), this.set_tab_index()
		}, b.prototype.register_observers = function() {
			return this.container.mousedown(e(function(a) {
				return this.container_mousedown(a)
			}, this)), this.container.mouseup(e(function(a) {
				return this.container_mouseup(a)
			}, this)), this.container.mouseenter(e(function(a) {
				return this.mouse_enter(a)
			}, this)), this.container.mouseleave(e(function(a) {
				return this.mouse_leave(a)
			}, this)), this.search_results.mouseup(e(function(a) {
				return this.search_results_mouseup(a)
			}, this)), this.search_results.mouseover(e(function(a) {
				return this.search_results_mouseover(a)
			}, this)), this.search_results.mouseout(e(function(a) {
				return this.search_results_mouseout(a)
			}, this)), this.form_field_jq.bind("liszt:updated", e(function(a) {
				return this.results_update_field(a)
			}, this)), this.search_field.blur(e(function(a) {
				return this.input_blur(a)
			}, this)), this.search_field.keyup(e(function(a) {
				return this.keyup_checker(a)
			}, this)), this.search_field.keydown(e(function(a) {
				return this.keydown_checker(a)
			}, this)), this.is_multiple ? (this.search_choices.click(e(function(a) {
				return this.choices_click(a)
			}, this)), this.search_field.focus(e(function(a) {
				return this.input_focus(a)
			}, this))) : void 0
		}, b.prototype.search_field_disabled = function() {
			return this.is_disabled = this.form_field_jq.attr("disabled"), this.is_disabled ? (this.container.addClass("chzn-disabled"), this.search_field.attr("disabled", !0), this.is_multiple || this.selected_item.unbind("focus", this.activate_action), this.close_field()) : (this.container.removeClass("chzn-disabled"), this.search_field.attr("disabled", !1), this.is_multiple ? void 0 : this.selected_item.bind("focus", this.activate_action))
		}, b.prototype.container_mousedown = function(b) {
			var c;
			return this.is_disabled ? void 0 : (c = null != b ? a(b.target).hasClass("search-choice-close") : !1, b && "mousedown" === b.type && b.stopPropagation(), this.pending_destroy_click || c ? this.pending_destroy_click = !1 : (this.active_field ? this.is_multiple || !b || a(b.target) !== this.selected_item && !a(b.target).parents("a.chzn-single").length || (b.preventDefault(), this.results_toggle()) : (this.is_multiple && this.search_field.val(""), a(document).click(this.click_test_action), this.results_show()), this.activate_field()))
		}, b.prototype.container_mouseup = function(a) {
			return "ABBR" === a.target.nodeName ? this.results_reset(a) : void 0
		}, b.prototype.mouse_enter = function() {
			return this.mouse_on_container = !0
		}, b.prototype.mouse_leave = function() {
			return this.mouse_on_container = !1
		}, b.prototype.input_focus = function() {
			return this.active_field ? void 0 : setTimeout(e(function() {
				return this.container_mousedown()
			}, this), 50)
		}, b.prototype.input_blur = function() {
			return this.mouse_on_container ? void 0 : (this.active_field = !1, setTimeout(e(function() {
				return this.blur_test()
			}, this), 100))
		}, b.prototype.blur_test = function() {
			return !this.active_field && this.container.hasClass("chzn-container-active") ? this.close_field() : void 0
		}, b.prototype.close_field = function() {
			return a(document).unbind("click", this.click_test_action), this.is_multiple || (this.selected_item.attr("tabindex", this.search_field.attr("tabindex")), this.search_field.attr("tabindex", -1)), this.active_field = !1, this.results_hide(), this.container.removeClass("chzn-container-active"), this.winnow_results_clear(), this.clear_backstroke(), this.show_search_field_default(), this.search_field_scale()
		}, b.prototype.activate_field = function() {
			return this.is_multiple || this.active_field || (this.search_field.attr("tabindex", this.selected_item.attr("tabindex")), this.selected_item.attr("tabindex", -1)), this.container.addClass("chzn-container-active"), this.active_field = !0, this.search_field.val(this.search_field.val()), this.search_field.focus()
		}, b.prototype.test_active_click = function(b) {
			return a(b.target).parents("#" + this.container_id).length ? this.active_field = !0 : this.close_field()
		}, b.prototype.results_build = function() {
			var a, b, c, e, f, g;
			for (c = new Date, this.parsing = !0, this.results_data = d.SelectParser.select_to_array(this.form_field), this.is_multiple && this.choices > 0 ? (this.search_choices.find("li.search-choice").remove(), this.choices = 0) : this.is_multiple || this.selected_item.find("span").text(this.default_text), a = "", g = this.results_data, e = 0, f = g.length; f > e; e++) b = g[e], b.group ? a += this.result_add_group(b) : b.empty || (a += this.result_add_option(b), b.selected && this.is_multiple ? this.choice_build(b) : b.selected && !this.is_multiple && (this.selected_item.find("span").text(b.text), this.allow_single_deselect && this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>')));
			return this.search_field_disabled(), this.show_search_field_default(), this.search_field_scale(), this.search_results.html(a), this.parsing = !1
		}, b.prototype.result_add_group = function(b) {
			return b.disabled ? "" : (b.dom_id = this.container_id + "_g_" + b.array_index, '<li id="' + b.dom_id + '" class="group-result">' + a("<div />").text(b.label).html() + "</li>")
		}, b.prototype.result_add_option = function(a) {
			var b, c;
			return a.disabled ? "" : (a.dom_id = this.container_id + "_o_" + a.array_index, b = a.selected && this.is_multiple ? [] : ["active-result"], a.selected && b.push("result-selected"), null != a.group_array_index && b.push("group-option"), "" !== a.classes && b.push(a.classes), c = "" !== a.style.cssText ? ' style="' + a.style + '"' : "", '<li id="' + a.dom_id + '" class="' + b.join(" ") + '"' + c + ">" + a.html + "</li>")
		}, b.prototype.results_update_field = function() {
			return this.result_clear_highlight(), this.result_single_selected = null, this.results_build()
		}, b.prototype.result_do_highlight = function(a) {
			var b, c, d, e, f;
			if (a.length) {
				if (this.result_clear_highlight(), this.result_highlight = a, this.result_highlight.addClass("highlighted"), d = parseInt(this.search_results.css("maxHeight"), 10), f = this.search_results.scrollTop(), e = d + f, c = this.result_highlight.position().top + this.search_results.scrollTop(), b = c + this.result_highlight.outerHeight(), b >= e) return this.search_results.scrollTop(b - d > 0 ? b - d : 0);
				if (f > c) return this.search_results.scrollTop(c)
			}
		}, b.prototype.result_clear_highlight = function() {
			return this.result_highlight && this.result_highlight.removeClass("highlighted"), this.result_highlight = null
		}, b.prototype.results_toggle = function() {
			return this.results_showing ? this.results_hide() : this.results_show()
		}, b.prototype.results_show = function() {
			var a;
			return this.is_multiple || (this.selected_item.addClass("chzn-single-with-drop"), this.result_single_selected && this.result_do_highlight(this.result_single_selected)), a = this.is_multiple ? this.container.height() : this.container.height() - 1, this.dropdown.css({
				top: a + "px",
				left: 0
			}), this.results_showing = !0, this.search_field.focus(), this.search_field.val(this.search_field.val()), this.winnow_results()
		}, b.prototype.results_hide = function() {
			return this.is_multiple || this.selected_item.removeClass("chzn-single-with-drop"), this.result_clear_highlight(), this.dropdown.css({
				left: "-9000px"
			}), this.results_showing = !1
		}, b.prototype.set_tab_index = function() {
			var a;
			return this.form_field_jq.attr("tabindex") ? (a = this.form_field_jq.attr("tabindex"), this.form_field_jq.attr("tabindex", -1), this.is_multiple ? this.search_field.attr("tabindex", a) : (this.selected_item.attr("tabindex", a), this.search_field.attr("tabindex", -1))) : void 0
		}, b.prototype.show_search_field_default = function() {
			return this.is_multiple && this.choices < 1 && !this.active_field ? (this.search_field.val(this.default_text), this.search_field.addClass("default")) : (this.search_field.val(""), this.search_field.removeClass("default"))
		}, b.prototype.search_results_mouseup = function(b) {
			var c;
			return c = a(b.target).hasClass("active-result") ? a(b.target) : a(b.target).parents(".active-result").first(), c.length ? (this.result_highlight = c, this.result_select(b)) : void 0
		}, b.prototype.search_results_mouseover = function(b) {
			var c;
			return c = a(b.target).hasClass("active-result") ? a(b.target) : a(b.target).parents(".active-result").first(), c ? this.result_do_highlight(c) : void 0
		}, b.prototype.search_results_mouseout = function(b) {
			return a(b.target).hasClass("active-result") ? this.result_clear_highlight() : void 0
		}, b.prototype.choices_click = function(b) {
			return b.preventDefault(), !this.active_field || a(b.target).hasClass("search-choice") || this.results_showing ? void 0 : this.results_show()
		}, b.prototype.choice_build = function(b) {
			var c, d;
			return c = this.container_id + "_c_" + b.array_index, this.choices += 1, this.search_container.before('<li class="search-choice" id="' + c + '"><span>' + b.html + '</span><a href="javascript:void(0)" class="search-choice-close" rel="' + b.array_index + '"></a></li>'), d = a("#" + c).find("a").first(), d.click(e(function(a) {
				return this.choice_destroy_link_click(a)
			}, this))
		}, b.prototype.choice_destroy_link_click = function(b) {
			return b.preventDefault(), this.is_disabled ? b.stopPropagation : (this.pending_destroy_click = !0, this.choice_destroy(a(b.target)))
		}, b.prototype.choice_destroy = function(a) {
			return this.choices -= 1, this.show_search_field_default(), this.is_multiple && this.choices > 0 && this.search_field.val().length < 1 && this.results_hide(), this.result_deselect(a.attr("rel")), a.parents("li").first().remove()
		}, b.prototype.results_reset = function(b) {
			return this.form_field.options[0].selected = !0, this.selected_item.find("span").text(this.default_text), this.show_search_field_default(), a(b.target).remove(), this.form_field_jq.trigger("change"), this.active_field ? this.results_hide() : void 0
		}, b.prototype.result_select = function(a) {
			var b, c, d, e;
			return this.result_highlight ? (b = this.result_highlight, c = b.attr("id"), this.result_clear_highlight(), this.is_multiple ? this.result_deactivate(b) : (this.search_results.find(".result-selected").removeClass("result-selected"), this.result_single_selected = b), b.addClass("result-selected"), e = c.substr(c.lastIndexOf("_") + 1), d = this.results_data[e], d.selected = !0, this.form_field.options[d.options_index].selected = !0, this.is_multiple ? this.choice_build(d) : (this.selected_item.find("span").first().text(d.text), this.allow_single_deselect && this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>')), a.metaKey && this.is_multiple || this.results_hide(), this.search_field.val(""), this.form_field_jq.trigger("change"), this.search_field_scale()) : void 0
		}, b.prototype.result_activate = function(a) {
			return a.addClass("active-result")
		}, b.prototype.result_deactivate = function(a) {
			return a.removeClass("active-result")
		}, b.prototype.result_deselect = function(b) {
			var c, d;
			return d = this.results_data[b], d.selected = !1, this.form_field.options[d.options_index].selected = !1, c = a("#" + this.container_id + "_o_" + b), c.removeClass("result-selected").addClass("active-result").show(), this.result_clear_highlight(), this.winnow_results(), this.form_field_jq.trigger("change"), this.search_field_scale()
		}, b.prototype.results_search = function() {
			return this.results_showing ? this.winnow_results() : this.results_show()
		}, b.prototype.winnow_results = function() {
			var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r;
			for (j = new Date, this.no_results_clear(), h = 0, i = this.search_field.val() === this.default_text ? "" : a("<div/>").text(a.trim(this.search_field.val())).html(), f = new RegExp("^" + i.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), "i"), m = new RegExp(i.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), "i"), r = this.results_data, n = 0, p = r.length; p > n; n++) if (c = r[n], !c.disabled && !c.empty) if (c.group) a("#" + c.dom_id).hide();
			else if (!this.is_multiple || !c.selected) {
				if (b = !1, g = c.dom_id, f.test(c.html)) b = !0, h += 1;
				else if ((c.html.indexOf(" ") >= 0 || 0 === c.html.indexOf("[")) && (e = c.html.replace(/\[|\]/g, "").split(" "), e.length)) for (o = 0, q = e.length; q > o; o++) d = e[o], f.test(d) && (b = !0, h += 1);
				b ? (i.length ? (k = c.html.search(m), l = c.html.substr(0, k + i.length) + "</em>" + c.html.substr(k + i.length), l = l.substr(0, k) + "<em>" + l.substr(k)) : l = c.html, a("#" + g).html !== l && a("#" + g).html(l), this.result_activate(a("#" + g)), null != c.group_array_index && a("#" + this.results_data[c.group_array_index].dom_id).show()) : (this.result_highlight && g === this.result_highlight.attr("id") && this.result_clear_highlight(), this.result_deactivate(a("#" + g)))
			}
			return 1 > h && i.length ? this.no_results(i) : this.winnow_results_set_highlight()
		}, b.prototype.winnow_results_clear = function() {
			var b, c, d, e, f;
			for (this.search_field.val(""), c = this.search_results.find("li"), f = [], d = 0, e = c.length; e > d; d++) b = c[d], b = a(b), f.push(b.hasClass("group-result") ? b.show() : this.is_multiple && b.hasClass("result-selected") ? void 0 : this.result_activate(b));
			return f
		}, b.prototype.winnow_results_set_highlight = function() {
			var a, b;
			return this.result_highlight || (b = this.is_multiple ? [] : this.search_results.find(".result-selected.active-result"), a = b.length ? b.first() : this.search_results.find(".active-result").first(), null == a) ? void 0 : this.result_do_highlight(a)
		}, b.prototype.no_results = function(b) {
			var c;
			return c = a('<li class="no-results">' + this.results_none_found + ' "<span></span>"</li>'), c.find("span").first().html(b), this.search_results.append(c)
		}, b.prototype.no_results_clear = function() {
			return this.search_results.find(".no-results").remove()
		}, b.prototype.keydown_arrow = function() {
			var b, c;
			return this.result_highlight ? this.results_showing && (c = this.result_highlight.nextAll("li.active-result").first(), c && this.result_do_highlight(c)) : (b = this.search_results.find("li.active-result").first(), b && this.result_do_highlight(a(b))), this.results_showing ? void 0 : this.results_show()
		}, b.prototype.keyup_arrow = function() {
			var a;
			return this.results_showing || this.is_multiple ? this.result_highlight ? (a = this.result_highlight.prevAll("li.active-result"), a.length ? this.result_do_highlight(a.first()) : (this.choices > 0 && this.results_hide(), this.result_clear_highlight())) : void 0 : this.results_show()
		}, b.prototype.keydown_backstroke = function() {
			return this.pending_backstroke ? (this.choice_destroy(this.pending_backstroke.find("a").first()), this.clear_backstroke()) : (this.pending_backstroke = this.search_container.siblings("li.search-choice").last(), this.pending_backstroke.addClass("search-choice-focus"))
		}, b.prototype.clear_backstroke = function() {
			return this.pending_backstroke && this.pending_backstroke.removeClass("search-choice-focus"), this.pending_backstroke = null
		}, b.prototype.keyup_checker = function(a) {
			var b, c;
			switch (b = null != (c = a.which) ? c : a.keyCode, this.search_field_scale(), b) {
			case 8:
				if (this.is_multiple && this.backstroke_length < 1 && this.choices > 0) return this.keydown_backstroke();
				if (!this.pending_backstroke) return this.result_clear_highlight(), this.results_search();
				break;
			case 13:
				if (a.preventDefault(), this.results_showing) return this.result_select(a);
				break;
			case 27:
				if (this.results_showing) return this.results_hide();
				break;
			case 9:
			case 38:
			case 40:
			case 16:
			case 91:
			case 17:
				break;
			default:
				return this.results_search()
			}
		}, b.prototype.keydown_checker = function(a) {
			var b, c;
			switch (b = null != (c = a.which) ? c : a.keyCode, this.search_field_scale(), 8 !== b && this.pending_backstroke && this.clear_backstroke(), b) {
			case 8:
				this.backstroke_length = this.search_field.val().length;
				break;
			case 9:
				this.mouse_on_container = !1;
				break;
			case 13:
				a.preventDefault();
				break;
			case 38:
				a.preventDefault(), this.keyup_arrow();
				break;
			case 40:
				this.keydown_arrow()
			}
		}, b.prototype.search_field_scale = function() {
			var b, c, d, e, f, g, h, i, j;
			if (this.is_multiple) {
				for (d = 0, h = 0, f = "position:absolute; left: -1000px; top: -1000px; display:none;", g = ["font-size", "font-style", "font-weight", "font-family", "line-height", "text-transform", "letter-spacing"], i = 0, j = g.length; j > i; i++) e = g[i], f += e + ":" + this.search_field.css(e) + ";";
				return c = a("<div />", {
					style: f
				}), c.text(this.search_field.val()), a("body").append(c), h = c.width() + 25, c.remove(), h > this.f_width - 10 && (h = this.f_width - 10), this.search_field.css({
					width: h + "px"
				}), b = this.container.height(), this.dropdown.css({
					top: b + "px"
				})
			}
		}, b.prototype.generate_field_id = function() {
			var a;
			return a = this.generate_random_id(), this.form_field.id = a, a
		}, b.prototype.generate_random_id = function() {
			var b;
			for (b = "sel" + this.generate_random_char() + this.generate_random_char() + this.generate_random_char(); a("#" + b).length > 0;) b += this.generate_random_char();
			return b
		}, b.prototype.generate_random_char = function() {
			var a, b, c;
			return a = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ", c = Math.floor(Math.random() * a.length), b = a.substring(c, c + 1)
		}, b
	}(), c = function(a) {
		var b;
		return b = a.outerWidth() - a.width()
	}, d.get_side_border_padding = c
}.call(this), function() {
	var a;
	a = function() {
		function a() {
			this.options_index = 0, this.parsed = []
		}
		return a.prototype.add_node = function(a) {
			return "OPTGROUP" === a.nodeName ? this.add_group(a) : this.add_option(a)
		}, a.prototype.add_group = function(a) {
			var b, c, d, e, f, g;
			for (b = this.parsed.length, this.parsed.push({
				array_index: b,
				group: !0,
				label: a.label,
				children: 0,
				disabled: a.disabled
			}), f = a.childNodes, g = [], d = 0, e = f.length; e > d; d++) c = f[d], g.push(this.add_option(c, b, a.disabled));
			return g
		}, a.prototype.add_option = function(a, b, c) {
			return "OPTION" === a.nodeName ? ("" !== a.text ? (null != b && (this.parsed[b].children += 1), this.parsed.push({
				array_index: this.parsed.length,
				options_index: this.options_index,
				value: a.value,
				text: a.text,
				html: a.innerHTML,
				selected: a.selected,
				disabled: c === !0 ? c : a.disabled,
				group_array_index: b,
				classes: a.className,
				style: a.style.cssText
			})) : this.parsed.push({
				array_index: this.parsed.length,
				options_index: this.options_index,
				empty: !0
			}), this.options_index += 1) : void 0
		}, a
	}(), a.select_to_array = function(b) {
		var c, d, e, f, g;
		for (d = new a, g = b.childNodes, e = 0, f = g.length; f > e; e++) c = g[e], d.add_node(c);
		return d.parsed
	}, this.SelectParser = a
}.call(this);
var ccm_totalAdvancedSearchFields = 0,
	ccm_alLaunchType = new Array,
	ccm_alActiveAssetField = "",
	ccm_alProcessorTarget = "",
	ccm_alDebug = !1;
ccm_triggerSelectFile = function(a, b) {
	if (null == b) var b = ccm_alActiveAssetField;
	var c = $("#" + b + "-fm-selected"),
		d = $("#" + b + "-fm-display");
	d.hide(), c.show(), c.load(CCM_TOOLS_PATH + "/files/selector_data?fID=" + a + "&ccm_file_selected_field=" + b, function() {
		c.attr("fID", a), c.attr("ccm-file-manager-can-view", c.children("div").attr("ccm-file-manager-can-view")), c.attr("ccm-file-manager-can-edit", c.children("div").attr("ccm-file-manager-can-edit")), c.attr("ccm-file-manager-can-admin", c.children("div").attr("ccm-file-manager-can-admin")), c.attr("ccm-file-manager-can-replace", c.children("div").attr("ccm-file-manager-can-replace")), c.attr("ccm-file-manager-instance", b), c.click(function(a) {
			a.stopPropagation(), ccm_alActivateMenu($(this), a)
		}), "function" == typeof ccm_triggerSelectFileComplete && ccm_triggerSelectFileComplete(a, b)
	});
	var e = $("#" + b + "-fm-value");
	e.attr("value", a), ccm_alSetupFileProcessor()
}, ccm_alGetFileData = function(a, b) {
	$.getJSON(CCM_TOOLS_PATH + "/files/get_data.php?fID=" + a, function(a) {
		b(a)
	})
}, ccm_clearFile = function(a, b) {
	a.stopPropagation();
	var c = $("#" + b + "-fm-selected"),
		d = $("#" + b + "-fm-display"),
		e = $("#" + b + "-fm-value");
	e.attr("value", 0), c.hide(), d.show()
}, ccm_activateFileManager = function(a, b) {
	ccm_alLaunchType[b] = a, ccm_alSetupSelectFiles(b), $(document).click(function(a) {
		a.stopPropagation(), ccm_alSelectNone()
	}), ccm_setupAdvancedSearch(b), "DASHBOARD" == a && $(".dialog-launch").dialog(), ccm_alSetupCheckboxes(b), ccm_alSetupFileProcessor(), ccm_alSetupSingleUploadForm(), $("form#ccm-" + b + "-advanced-search select[name=fssID]").change(function() {
		if ("DASHBOARD" == a) window.location.href = CCM_DISPATCHER_FILENAME + "/dashboard/files/search?fssID=" + $(this).val();
		else {
			jQuery.fn.dialog.showLoader();
			var c = $("div#ccm-" + b + "-overlay-wrapper input[name=dialogAction]").val() + "&refreshDialog=1&fssID=" + $(this).val();
			$.get(c, function(a) {
				jQuery.fn.dialog.hideLoader(), $("div#ccm-" + b + "-overlay-wrapper").html(a), $("div#ccm-" + b + "-overlay-wrapper a.dialog-launch").dialog()
			})
		}
	}), ccm_searchActivatePostFunction[b] = function() {
		ccm_alSetupCheckboxes(b), ccm_alSetupSelectFiles(b), ccm_alSetupSingleUploadForm()
	}
}, ccm_alSetupSingleUploadForm = function() {
	$(".ccm-file-manager-submit-single").submit(function() {
		$(this).attr("target", ccm_alProcessorTarget), ccm_alSubmitSingle($(this).get(0))
	})
}, ccm_activateFileSelectors = function() {
	$(".ccm-file-manager-launch").unbind(), $(".ccm-file-manager-launch").click(function() {
		ccm_alLaunchSelectorFileManager($(this).parent().attr("ccm-file-manager-field"))
	})
}, ccm_alLaunchSelectorFileManager = function(a) {
	ccm_alActiveAssetField = a;
	var b = "",
		c = $("#" + a + "-fm-display input.ccm-file-manager-filter");
	if (c.length) {
		var d, e = {};
		for (i = 0; i < c.length; i++) d = $(c[i]).attr("name"), d in e || (e[d] = []), e[d].push($(c[i]).attr("value"));
		for (d in e) 1 == e[d].length ? b += "&" + d + "=" + encodeURIComponent(e[d][0]) : $.each(e[d], function(a, c) {
			b += "&" + d + "[]=" + encodeURIComponent(c)
		})
	}
	ccm_launchFileManager(b)
}, ccm_launchFileManager = function(a) {
	$.fn.dialog.open({
		width: "90%",
		height: "70%",
		appendButtons: !0,
		modal: !1,
		href: CCM_TOOLS_PATH + "/files/search_dialog?ocID=" + CCM_CID + "&search=1" + a,
		title: ccmi18n_filemanager.title
	})
}, ccm_launchFileSetPicker = function(a) {
	$.fn.dialog.open({
		width: 500,
		height: 160,
		modal: !1,
		href: CCM_TOOLS_PATH + "/files/pick_set?oldFSID=" + a,
		title: ccmi18n_filemanager.sets
	})
}, ccm_alSubmitSetsForm = function(a) {
	ccm_deactivateSearchResults(a), jQuery.fn.dialog.showLoader(), $("#ccm-" + a + "-add-to-set-form").ajaxSubmit(function() {
		jQuery.fn.dialog.closeTop(), jQuery.fn.dialog.hideLoader(), $("#ccm-" + a + "-advanced-search").ajaxSubmit(function(b) {
			$("#ccm-" + a + "-sets-search-wrapper").load(CCM_TOOLS_PATH + "/files/search_sets_reload", {
				searchInstance: a
			}, function() {
				$(".chosen-select").chosen(ccmi18n_chosen), ccm_parseAdvancedSearchResponse(b, a)
			})
		})
	})
}, ccm_alSubmitPasswordForm = function(a) {
	ccm_deactivateSearchResults(a), $("#ccm-" + a + "-password-form").ajaxSubmit(function() {
		jQuery.fn.dialog.closeTop(), $("#ccm-" + a + "-advanced-search").ajaxSubmit(function(b) {
			ccm_parseAdvancedSearchResponse(b, a)
		})
	})
}, ccm_alSubmitStorageForm = function(a) {
	ccm_deactivateSearchResults(a), $("#ccm-" + a + "-storage-form").ajaxSubmit(function() {
		jQuery.fn.dialog.closeTop(), $("#ccm-" + a + "-advanced-search").ajaxSubmit(function(b) {
			ccm_parseAdvancedSearchResponse(b, a)
		})
	})
}, ccm_alSubmitPermissionsForm = function(a) {
	ccm_deactivateSearchResults(a), $("#ccm-" + a + "-permissions-form").ajaxSubmit(function() {
		jQuery.fn.dialog.closeTop(), $("#ccm-" + a + "-advanced-search").ajaxSubmit(function(b) {
			ccm_parseAdvancedSearchResponse(b, a)
		})
	})
}, ccm_alSetupSetsForm = function(a) {
	$("#fsAddToSearchName").liveUpdate("ccm-file-search-add-to-sets-list", "fileset"), $(".ccm-file-set-add-cb a").each(function() {
		var a = $(this),
			b = a.attr("ccm-tri-state-startup");
		$(this).click(function() {
			var a = $(this).attr("ccm-tri-state-selected"),
				c = 0;
			switch (a) {
			case "0":
				c = "1" == b ? "1" : "2";
				break;
			case "1":
				c = "2";
				break;
			case "2":
				c = "0"
			}
			$(this).attr("ccm-tri-state-selected", c), $(this).parent().find("input").val(c), $(this).find("img").attr("src", CCM_IMAGE_PATH + "/checkbox_state_" + c + ".png")
		})
	}), $("#ccm-" + a + "-add-to-set-form input[name=fsNew]").click(function() {
		$(this).prop("checked") || $("#ccm-" + a + "-add-to-set-form input[name=fsNewText]").val("")
	}), $("#ccm-" + a + "-add-to-set-form").submit(function() {
		return ccm_alSubmitSetsForm(a), !1
	})
}, ccm_alSetupPasswordForm = function() {
	$("#ccm-file-password-form").submit(function() {
		return ccm_alSubmitPasswordForm(), !1
	})
}, ccm_alRescanFiles = function() {
	var a = CCM_TOOLS_PATH + "/files/rescan?",
		b = arguments;
	for (i = 0; i < b.length; i++) a += "fID[]=" + b[i] + "&";
	$.fn.dialog.open({
		title: ccmi18n_filemanager.rescan,
		href: a,
		width: 350,
		modal: !1,
		height: 200,
		onClose: function() {
			1 == b.length && ($("#ccm-file-properties-wrapper").html(""), jQuery.fn.dialog.showLoader(), $("#ccm-file-properties-wrapper").load(CCM_TOOLS_PATH + "/files/properties?fID=" + b[0] + "&reload=1", !1, function() {
				jQuery.fn.dialog.hideLoader(), $(this).find(".dialog-launch").dialog()
			}))
		}
	})
}, ccm_alSelectPermissionsEntity = function(a, b, c) {
	var d = $("#ccm-file-permissions-entity-base").html();
	$("#ccm-file-permissions-entities-wrapper").append('<div class="ccm-file-permissions-entity">' + d + "</div>");
	var e = $(".ccm-file-permissions-entity"),
		f = e[e.length - 1];
	$(f).find("h3 span").html(c), $(f).find("input[type=hidden]").val(a + "_" + b), $(f).find("select").each(function() {
		$(this).attr("name", $(this).attr("name") + "_" + a + "_" + b)
	}), $(f).find("div.ccm-file-access-extensions input[type=checkbox]").each(function() {
		$(this).attr("name", $(this).attr("name") + "_" + a + "_" + b + "[]")
	}), ccm_alActivateFilePermissionsSelector()
}, ccm_alActivateFilePermissionsSelector = function() {
	$(".ccm-file-access-add select").unbind(), $(".ccm-file-access-add select").change(function() {
		var a = $(this).parents("div.ccm-file-permissions-entity")[0];
		$(this).val() == ccmi18n_filemanager.PTYPE_CUSTOM ? $(a).find("div.ccm-file-access-add-extensions").show() : $(a).find("div.ccm-file-access-add-extensions").hide()
	}), $(".ccm-file-access-file-manager select").change(function() {
		var a = $(this).parents("div.ccm-file-permissions-entity")[0];
		$(this).val() != ccmi18n_filemanager.PTYPE_NONE ? ($(a).find(".ccm-file-access-add").show(), $(a).find(".ccm-file-access-edit").show(), $(a).find(".ccm-file-access-admin").show()) : ($(a).find(".ccm-file-access-add").hide(), $(a).find(".ccm-file-access-edit").hide(), $(a).find(".ccm-file-access-admin").hide(), $(a).find("div.ccm-file-access-add-extensions").hide())
	}), $("a.ccm-file-permissions-remove").click(function() {
		$(this).parent().parent().fadeOut(100, function() {
			$(this).remove()
		})
	}), $("input[name=toggleCanAddExtension]").unbind(), $("input[name=toggleCanAddExtension]").click(function() {
		var a = $(this).parent().parent().find("div.ccm-file-access-extensions");
		1 == $(this).prop("checked") ? a.find("input").attr("checked", !0) : a.find("input").attr("checked", !1)
	})
}, ccm_alSetupVersionSelector = function() {
	$("#ccm-file-versions-grid input[type=radio]").click(function() {
		$("#ccm-file-versions-grid tr").removeClass("ccm-file-versions-grid-active");
		var a = $(this).parent().parent(),
			b = a.attr("fID"),
			c = a.attr("fvID"),
			d = "task=approve_version&fID=" + b + "&fvID=" + c;
		$.post(CCM_TOOLS_PATH + "/files/properties", d, function() {
			a.addClass("ccm-file-versions-grid-active"), a.find("td").show("highlight", {
				color: "#FFF9BB"
			})
		})
	}), $(".ccm-file-versions-remove").click(function() {
		var a = $(this).parent().parent(),
			b = a.attr("fID"),
			c = a.attr("fvID"),
			d = "task=delete_version&fID=" + b + "&fvID=" + c;
		return $.post(CCM_TOOLS_PATH + "/files/properties", d, function() {
			a.fadeOut(200, function() {
				a.remove()
			})
		}), !1
	})
}, ccm_alDeleteFiles = function(a) {
	$("#ccm-" + a + "-delete-form").ajaxSubmit(function(b) {
		ccm_parseJSON(b, function() {
			jQuery.fn.dialog.closeTop(), ccm_deactivateSearchResults(a), $("#ccm-" + a + "-advanced-search").ajaxSubmit(function(b) {
				ccm_parseAdvancedSearchResponse(b, a)
			})
		})
	})
}, ccm_alDuplicateFiles = function(searchInstance) {
	$("#ccm-" + searchInstance + "-duplicate-form").ajaxSubmit(function(resp) {
		ccm_parseJSON(resp, function() {
			jQuery.fn.dialog.closeTop(), ccm_deactivateSearchResults(searchInstance);
			var r = eval("(" + resp + ")");
			$("#ccm-" + searchInstance + "-advanced-search").ajaxSubmit(function(a) {
				ccm_parseAdvancedSearchResponse(a, searchInstance);
				var b = new Array;
				for (i = 0; i < r.fID.length; i++) fID = r.fID[i], ccm_uploadedFiles.push(fID), b.push(fID);
				ccm_alRefresh(b, searchInstance), ccm_filesUploadedDialog(searchInstance)
			})
		})
	})
}, ccm_alSetupSelectFiles = function(a) {
	$(".ccm-file-list").unbind(), $(".ccm-file-list tr.ccm-list-record").click(function(a) {
		a.stopPropagation(), ccm_alActivateMenu($(this), a)
	}), $(".ccm-file-list img.ccm-star").click(function(a) {
		a.stopPropagation();
		var b = $(a.target).parents("tr.ccm-list-record")[0].id;
		b = b.substring(3), ccm_starFile(a.target, b)
	}), "DASHBOARD" == ccm_alLaunchType[a] && $(".ccm-file-list-thumbnail").hover(function() {
		var a = $(this).attr("fID"),
			b = $("#fID" + a + "hoverThumbnail");
		if (b.length > 0) {
			var c = b.find("div"),
				d = b.position();
			c.css("top", d.top), c.css("left", d.left), c.show()
		}
	}, function() {
		var a = $(this).attr("fID"),
			b = $("#fID" + a + "hoverThumbnail"),
			c = b.find("div");
		c.hide()
	})
}, ccm_alSetupCheckboxes = function(a) {
	if ($("#ccm-" + a + "-list-cb-all").unbind(), $("#ccm-" + a + "-list-cb-all").click(function() {
		ccm_hideMenus(), 1 == $(this).prop("checked") ? ($("#ccm-" + a + "-search-results td.ccm-file-list-cb input[type=checkbox]").attr("checked", !0), $("#ccm-" + a + "-list-multiple-operations").attr("disabled", !1)) : ($("#ccm-" + a + "-search-results td.ccm-file-list-cb input[type=checkbox]").attr("checked", !1), $("#ccm-" + a + "-list-multiple-operations").attr("disabled", !0))
	}), $("#ccm-" + a + "-search-results td.ccm-file-list-cb input[type=checkbox]").click(function(b) {
		b.stopPropagation(), ccm_hideMenus(), ccm_alRescanMultiFileMenu(a)
	}), $("#ccm-" + a + "-search-results td.ccm-file-list-cb").click(function(b) {
		b.stopPropagation(), ccm_hideMenus(), $(this).find("input[type=checkbox]").click(), ccm_alRescanMultiFileMenu(a)
	}), "DASHBOARD" != ccm_alLaunchType[a] && "BROWSE" != ccm_alLaunchType[a]) {
		var b = ccmi18n_filemanager.select;
		$("#ccm-" + a + "-list-multiple-operations option:eq(0)").after('<option value="choose">' + b + "</option>")
	}
	$("#ccm-" + a + "-list-multiple-operations").change(function() {
		var b = $(this).val(),
			c = ccm_alGetSelectedFileIDs(a);
		switch (b) {
		case "choose":
			var d = new Array;
			$("#ccm-" + a + "-search-results td.ccm-file-list-cb input[type=checkbox]:checked").each(function() {
				d.push($(this).val())
			}), ccm_alSelectFile(d, !0);
			break;
		case "delete":
			jQuery.fn.dialog.open({
				width: 500,
				height: 400,
				modal: !1,
				appendButtons: !0,
				href: CCM_TOOLS_PATH + "/files/delete?" + c + "&searchInstance=" + a,
				title: ccmi18n_filemanager.deleteFile
			});
			break;
		case "duplicate":
			jQuery.fn.dialog.open({
				width: 500,
				height: 400,
				modal: !1,
				href: CCM_TOOLS_PATH + "/files/duplicate?" + c + "&searchInstance=" + a,
				title: ccmi18n_filemanager.duplicateFile
			});
			break;
		case "sets":
			jQuery.fn.dialog.open({
				width: 500,
				height: 400,
				modal: !1,
				href: CCM_TOOLS_PATH + "/files/add_to?" + c + "&searchInstance=" + a,
				title: ccmi18n_filemanager.sets
			});
			break;
		case "properties":
			jQuery.fn.dialog.open({
				width: 690,
				height: 440,
				modal: !1,
				href: CCM_TOOLS_PATH + "/files/bulk_properties?" + c + "&searchInstance=" + a,
				title: ccmi18n.properties
			});
			break;
		case "rescan":
			jQuery.fn.dialog.open({
				width: 350,
				height: 200,
				modal: !1,
				href: CCM_TOOLS_PATH + "/files/rescan?" + c + "&searchInstance=" + a,
				title: ccmi18n_filemanager.rescan,
				onClose: function() {
					$("#ccm-" + a + "-advanced-search").submit()
				}
			});
			break;
		case "download":
			window.frames[ccm_alProcessorTarget].location = CCM_TOOLS_PATH + "/files/download?" + c
		}
		$(this).get(0).selectedIndex = 0
	}), ccm_alSetupFileSetSearch(a)
}, ccm_alSetupFileSetSearch = function(a) {
	$("#ccm-" + a + "-sets-search-wrapper select").chosen(ccmi18n_chosen).unbind(), $("#ccm-" + a + "-sets-search-wrapper select").chosen(ccmi18n_chosen).change(function() {
		$("#ccm-" + a + "-sets-search-wrapper option:selected");
		$("#ccm-" + a + "-advanced-search").submit()
	})
}, ccm_alGetSelectedFileIDs = function(a) {
	var b = "";
	return $("#ccm-" + a + "-search-results td.ccm-file-list-cb input[type=checkbox]:checked").each(function() {
		b += "fID[]=" + $(this).val() + "&"
	}), b
}, ccm_alRescanMultiFileMenu = function(a) {
	$("#ccm-" + a + "-search-results td.ccm-file-list-cb input[type=checkbox]:checked").length > 0 ? $("#ccm-" + a + "-list-multiple-operations").attr("disabled", !1) : $("#ccm-" + a + "-list-multiple-operations").attr("disabled", !0)
}, ccm_alSetupFileProcessor = function() {
	if ("" != ccm_alProcessorTarget) return !1;
	var a, b = parseInt((new Date).getTime().toString().substring(0, 10));
	try {
		a = document.createElement('<iframe name="ccm-al-upload-processor' + b + '">')
	} catch (c) {
		a = document.createElement("iframe")
	}
	a.id = "ccm-al-upload-processor" + b, a.name = "ccm-al-upload-processor" + b, a.style.border = "0px", a.style.width = "0px", a.style.height = "0px", a.style.display = "none", document.body.appendChild(a), ccm_alProcessorTarget = ccm_alDebug ? "_blank" : "ccm-al-upload-processor" + b
}, ccm_alSubmitSingle = function(a) {
	return "" == $(a).find(".ccm-al-upload-single-file").val() ? !1 : ($(a).find(".ccm-al-upload-single-submit").hide(), void $(a).find(".ccm-al-upload-single-loader").show())
}, ccm_alResetSingle = function() {
	$(".ccm-al-upload-single-file").val(""), $(".ccm-al-upload-single-loader").hide(), $(".ccm-al-upload-single-submit").show()
};
var ccm_uploadedFiles = [];
ccm_filesUploadedDialog = function(a) {
	document.getElementById("ccm-file-upload-multiple-tab") && jQuery.fn.dialog.closeTop();
	for (var b = "", c = 0; c < ccm_uploadedFiles.length; c++) b = b + "&fID[]=" + ccm_uploadedFiles[c];
	jQuery.fn.dialog.open({
		width: 690,
		height: 440,
		modal: !1,
		href: CCM_TOOLS_PATH + "/files/bulk_properties/?" + b + "&uploaded=true&searchInstance=" + a,
		onClose: function() {
			ccm_deactivateSearchResults(a), $("#ccm-" + a + "-advanced-search").ajaxSubmit(function(b) {
				ccm_parseAdvancedSearchResponse(b, a)
			})
		},
		title: ccmi18n_filemanager.uploadComplete
	}), ccm_uploadedFiles = []
}, ccm_alSetupUploadDetailsForm = function(a) {
	$("#ccm-" + a + "-update-uploaded-details-form").submit(function() {
		return ccm_alSubmitUploadDetailsForm(a), !1
	})
}, ccm_alSubmitUploadDetailsForm = function(searchInstance) {
	jQuery.fn.dialog.showLoader(), $("#ccm-" + searchInstance + "-update-uploaded-details-form").ajaxSubmit(function(r1) {
		var r1a = eval("(" + r1 + ")"),
			form = $("#ccm-" + searchInstance + "-advanced-search");
		form.length > 0 ? form.ajaxSubmit(function(a) {
			$("#ccm-" + searchInstance + "-sets-search-wrapper").load(CCM_TOOLS_PATH + "/files/search_sets_reload", {
				searchInstance: searchInstance
			}, function() {
				jQuery.fn.dialog.hideLoader(), jQuery.fn.dialog.closeTop(), ccm_parseAdvancedSearchResponse(a, searchInstance), ccm_alHighlightFileIDArray(r1a)
			})
		}) : (jQuery.fn.dialog.hideLoader(), jQuery.fn.dialog.closeTop())
	})
}, ccm_alRefresh = function(a, b, c) {
	var d = a;
	ccm_deactivateSearchResults(b), $("#ccm-" + b + "-search-results").load(CCM_TOOLS_PATH + "/files/search_results", {
		ccm_order_by: "fvDateAdded",
		ccm_order_dir: "desc",
		fileSelector: c,
		searchType: ccm_alLaunchType[b],
		searchInstance: b
	}, function() {
		ccm_activateSearchResults(b), 0 != d && ccm_alHighlightFileIDArray(d), ccm_alSetupSelectFiles(b)
	})
}, ccm_alHighlightFileIDArray = function(a) {
	for (i = 0; i < a.length; i++) {
		var b = $("tr[fID=" + a[i] + "] td"),
			c = b.css("backgroundColor");
		b.animate({
			backgroundColor: "#FFF9BB"
		}, {
			queue: !0,
			duration: 1e3
		}).animate({
			backgroundColor: c
		}, 500)
	}
}, ccm_alSelectFile = function(a) {
	if ("function" == typeof ccm_chooseAsset) {
		var b = "";
		if ("object" == typeof a) for (i = 0; i < a.length; i++) b += "fID[]=" + a[i] + "&";
		else b += "fID=" + a;
		$.getJSON(CCM_TOOLS_PATH + "/files/get_data.php?" + b, function(a) {
			ccm_parseJSON(a, function() {
				for (i = 0; i < a.length; i++) ccm_chooseAsset(a[i]);
				jQuery.fn.dialog.closeTop()
			})
		})
	} else {
		if ("object" == typeof a) for (i = 0; i < a.length; i++) ccm_triggerSelectFile(a[i]);
		else ccm_triggerSelectFile(a);
		jQuery.fn.dialog.closeTop()
	}
}, ccm_alActivateMenu = function(a, b) {
	var c = $(a).find("div[ccm-file-manager-field]"),
		d = "";
	c.length > 0 && (d = c.attr("ccm-file-manager-field")), d || (d = ccm_alActiveAssetField), ccm_hideMenus();
	var e = $(a).attr("fID"),
		f = $(a).attr("ccm-file-manager-instance"),
		g = document.getElementById("ccm-al-menu" + e + f + d);
	if (g) g = $("#ccm-al-menu" + e + f + d);
	else {
		el = document.createElement("DIV"), el.id = "ccm-al-menu" + e + f + d, el.className = "ccm-menu ccm-ui", el.style.display = "block", el.style.visibility = "hidden", document.body.appendChild(el);
		var h = $("div[ccm-file-manager-field=" + d + "] input.ccm-file-manager-filter"),
			i = "";
		h.length > 0 && h.each(function() {
			i += "&" + $(this).attr("name") + "=" + $(this).attr("value")
		}); {
			$(a).attr("al-filepath")
		}
		g = $("#ccm-al-menu" + e + f + d), g.css("position", "absolute");
		var j = '<div class="popover"><div class="arrow"></div><div class="inner"><div class="content">';
		if (j += "<ul>", "DASHBOARD" != ccm_alLaunchType[f] && "BROWSE" != ccm_alLaunchType[f]) {
			var k = c.length > 0 ? "ccm_alLaunchSelectorFileManager('" + d + "')" : "ccm_alSelectFile(" + e + ")",
				l = c.length > 0 ? ccmi18n_filemanager.chooseNew : ccmi18n_filemanager.select;
			j += '<li><a class="ccm-menu-icon ccm-icon-choose-file-menu" dialog-modal="false" dialog-width="90%" dialog-height="70%" dialog-title="' + ccmi18n_filemanager.select + '" id="menuSelectFile' + e + '" href="javascript:void(0)" onclick="' + k + '">' + l + "</a></li>"
		}
		c.length > 0 && (j += '<li><a class="ccm-menu-icon ccm-icon-clear-file-menu" href="javascript:void(0)" id="menuClearFile' + e + f + d + '">' + ccmi18n_filemanager.clear + "</a></li>"), "DASHBOARD" != ccm_alLaunchType[f] && "BROWSE" != ccm_alLaunchType[f] && c.length > 0 && (j += '<li class="ccm-menu-separator"></li>'), j += "1" == $(a).attr("ccm-file-manager-can-view") ? '<li><a class="ccm-menu-icon ccm-icon-view dialog-launch" dialog-modal="false" dialog-append-buttons="true" dialog-width="90%" dialog-height="75%" dialog-title="' + ccmi18n_filemanager.view + '" id="menuView' + e + '" href="' + CCM_TOOLS_PATH + "/files/view?fID=" + e + '">' + ccmi18n_filemanager.view + "</a></li>" : '<li><a class="ccm-menu-icon ccm-icon-download-menu" href="javascript:void(0)" id="menuDownload' + e + '" onclick="window.frames[\'' + ccm_alProcessorTarget + "'].location='" + CCM_TOOLS_PATH + "/files/download?fID=" + e + "'\">" + ccmi18n_filemanager.download + "</a></li>", "1" == $(a).attr("ccm-file-manager-can-edit") && (j += '<li><a class="ccm-menu-icon ccm-icon-edit-menu dialog-launch" dialog-modal="false" dialog-width="90%" dialog-height="75%" dialog-title="' + ccmi18n_filemanager.edit + '" id="menuEdit' + e + '" href="' + CCM_TOOLS_PATH + "/files/edit?searchInstance=" + f + "&fID=" + e + i + '">' + ccmi18n_filemanager.edit + "</a></li>"), j += '<li><a class="ccm-menu-icon ccm-icon-properties-menu dialog-launch" dialog-modal="false" dialog-width="680" dialog-height="450" dialog-title="' + ccmi18n_filemanager.properties + '" id="menuProperties' + e + '" href="' + CCM_TOOLS_PATH + "/files/properties?searchInstance=" + f + "&fID=" + e + '">' + ccmi18n_filemanager.properties + "</a></li>", "1" == $(a).attr("ccm-file-manager-can-replace") && (j += '<li><a class="ccm-menu-icon ccm-icon-replace dialog-launch" dialog-modal="false" dialog-width="300" dialog-height="260" dialog-title="' + ccmi18n_filemanager.replace + '" id="menuFileReplace' + e + '" href="' + CCM_TOOLS_PATH + "/files/replace?searchInstance=" + f + "&fID=" + e + '">' + ccmi18n_filemanager.replace + "</a></li>"), "1" == $(a).attr("ccm-file-manager-can-duplicate") && (j += '<li><a class="ccm-menu-icon ccm-icon-copy-menu" id="menuFileDuplicate' + e + '" href="javascript:void(0)" onclick="ccm_alDuplicateFile(' + e + ",'" + f + "')\">" + ccmi18n_filemanager.duplicate + "</a></li>"), j += '<li><a class="ccm-menu-icon ccm-icon-sets dialog-launch" dialog-modal="false" dialog-width="500" dialog-height="400" dialog-title="' + ccmi18n_filemanager.sets + '" id="menuFileSets' + e + '" href="' + CCM_TOOLS_PATH + "/files/add_to?searchInstance=" + f + "&fID=" + e + '">' + ccmi18n_filemanager.sets + "</a></li>", ("1" == $(a).attr("ccm-file-manager-can-admin") || "1" == $(a).attr("ccm-file-manager-can-delete")) && (j += '<li class="ccm-menu-separator"></li>'), "1" == $(a).attr("ccm-file-manager-can-admin") && (j += '<li><a class="ccm-menu-icon ccm-icon-access-permissions dialog-launch" dialog-modal="false" dialog-width="400" dialog-height="450" dialog-title="' + ccmi18n_filemanager.permissions + '" id="menuFilePermissions' + e + '" href="' + CCM_TOOLS_PATH + "/files/permissions?searchInstance=" + f + "&fID=" + e + '">' + ccmi18n_filemanager.permissions + "</a></li>"), "1" == $(a).attr("ccm-file-manager-can-delete") && (j += '<li><a class="ccm-icon-delete-menu ccm-menu-icon dialog-launch" dialog-append-buttons="true" dialog-modal="false" dialog-width="500" dialog-height="200" dialog-title="' + ccmi18n_filemanager.deleteFile + '" id="menuDeleteFile' + e + '" href="' + CCM_TOOLS_PATH + "/files/delete?searchInstance=" + f + "&fID=" + e + '">' + ccmi18n_filemanager.deleteFile + "</a></li>"), j += "</ul>", j += "</div></div></div>", g.append(j), $(g).find("a").bind("click.hide-menu", function() {
			return ccm_hideMenus(), !1
		}), $("#ccm-al-menu" + e + f + d + " a.dialog-launch").dialog(), $("a#menuClearFile" + e + f + d).click(function(a) {
			ccm_clearFile(a, d), ccm_hideMenus()
		})
	}
	ccm_fadeInMenu(g, b)
}, ccm_alSelectNone = function() {
	ccm_hideMenus()
};
var checkbox_status = !1;
toggleCheckboxStatus = function(a) {
	if (checkbox_status) {
		for (i = 0; i < a.elements.length; i++)"checkbox" == a.elements[i].type && (a.elements[i].checked = !1);
		checkbox_status = !1
	} else {
		for (i = 0; i < a.elements.length; i++)"checkbox" == a.elements[i].type && (a.elements[i].checked = !0);
		checkbox_status = !0
	}
}, ccm_alDuplicateFile = function(fID, searchInstance) {
	var postStr = "fID=" + fID + "&searchInstance=" + searchInstance;
	$.post(CCM_TOOLS_PATH + "/files/duplicate", postStr, function(resp) {
		var r = eval("(" + resp + ")");
		if (1 == r.error) return ccmAlert.notice(ccmi18n.error, r.message), !1;
		var highlight = new Array;
		r.fID && (highlight.push(r.fID), ccm_alRefresh(highlight, searchInstance), ccm_uploadedFiles.push(r.fID), ccm_filesUploadedDialog(searchInstance))
	})
}, ccm_alSelectMultipleIncomingFiles = function(a) {
	$(a).prop("checked") ? $("input.ccm-file-select-incoming").attr("checked", !0) : $("input.ccm-file-select-incoming").attr("checked", !1)
}, ccm_starFile = function(a, b) {
	var c = ""; - 1 != $(a).attr("src").indexOf(CCM_STAR_STATES.unstarred) ? ($(a).attr("src", $(a).attr("src").replace(CCM_STAR_STATES.unstarred, CCM_STAR_STATES.starred)), c = "star") : ($(a).attr("src", $(a).attr("src").replace(CCM_STAR_STATES.starred, CCM_STAR_STATES.unstarred)), c = "unstar"), $.post(CCM_TOOLS_PATH + "/" + CCM_STAR_ACTION, {
		action: c,
		"file-id": b
	}, function() {})
}, jQuery.cookie = function(a, b, c) {
	if ("undefined" == typeof b) {
		var d = null;
		if (document.cookie && "" != document.cookie) for (var e = document.cookie.split(";"), f = 0; f < e.length; f++) {
			var g = jQuery.trim(e[f]);
			if (g.substring(0, a.length + 1) == a + "=") {
				d = decodeURIComponent(g.substring(a.length + 1));
				break
			}
		}
		return d
	}
	c = c || {}, null === b && (b = "", c = $.extend({}, c), c.expires = -1);
	var h = "";
	if (c.expires && ("number" == typeof c.expires || c.expires.toUTCString)) {
		var i;
		"number" == typeof c.expires ? (i = new Date, i.setTime(i.getTime() + 24 * c.expires * 60 * 60 * 1e3)) : i = c.expires, h = "; expires=" + i.toUTCString()
	}
	var j = c.path ? "; path=" + c.path : "",
		k = c.domain ? "; domain=" + c.domain : "",
		l = c.secure ? "; secure" : "";
	document.cookie = [a, "=", encodeURIComponent(b), h, j, k, l].join("")
};
var quickSaveLayoutObj, deleteLayoutObj, ccmLayoutEdit = {
	init: function() {
		this.showPresetDeleteIcon(), $("#ccmLayoutPresentIdSelector").change(function() {
			var a = parseInt($(this).val()),
				b = $("#ccmAreaLayoutForm_layoutID").val();
			if (jQuery.fn.dialog.showLoader(), a > 0) var c = $("#ccm-layout-refresh-action").val() + "&lpID=" + a;
			else var c = $("#ccm-layout-refresh-action").val() + "&layoutID=" + b;
			$.get(c, function(a) {
				$("#ccm-layout-edit-wrapper").html(a), jQuery.fn.dialog.hideLoader(), ccmLayoutEdit.showPresetDeleteIcon()
			})
		}), $("#layoutPresetActionNew input[name=layoutPresetAction]").click(function() {
			"create_new_preset" == $(this).val() && $(this).prop("checked") ? $("input[name=layoutPresetName]").attr("disabled", !1).focus() : $("input[name=layoutPresetName]").val("").attr("disabled", !0)
		}), $("#layoutPresetActions input[name=layoutPresetAction]").click(function() {
			"create_new_preset" == $(this).val() && $(this).prop("checked") ? $("input[name=layoutPresetNameAlt]").attr("disabled", !1).focus() : $("input[name=layoutPresetNameAlt]").val("").attr("disabled", !0)
		}), $("#layoutPresetActions").length > 0 && $("#ccmLayoutConfigOptions input, #ccmLayoutConfigOptions select").bind("change click", function() {
			$("#layoutPresetActions").show(), $("#layoutPresetActionNew").hide(), $("#ccmLayoutConfigOptions input, #ccmLayoutConfigOptions select").unbind("change click")
		})
	},
	showPresetDeleteIcon: function() {
		$("#ccmLayoutPresentIdSelector").val() > 0 ? $("#ccm-layout-delete-preset").show() : $("#ccm-layout-delete-preset").hide()
	},
	deletePreset: function() {
		var lpID = parseInt($("#ccmLayoutPresentIdSelector").val());
		if (lpID > 0) {
			if (!confirm(ccmi18n.confirmLayoutPresetDelete)) return !1;
			jQuery.fn.dialog.showLoader();
			var area = $("#ccmAreaLayoutForm_arHandle").val(),
				url = CCM_TOOLS_PATH + "/layout_services/?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(area) + "&task=deletePreset&lpID=" + lpID;
			$.get(url, function(r) {
				eval("var jObj=" + r), 1 != parseInt(jObj.success) ? alert(jObj.msg) : $("#ccmLayoutPresentIdSelector option[value='" + lpID + "']").remove(), jQuery.fn.dialog.hideLoader()
			})
		}
	}
};
$.widget.bridge("jqdialog", $.ui.dialog), jQuery.fn.dialog = function() {
	return arguments.length > 0 ? void $(this).jqdialog(arguments[0], arguments[1], arguments[2]) : $(this).is("div") ? void $(this).jqdialog() : $(this).each(function() {
		$(this).unbind("click.make-dialog").bind("click.make-dialog", function() {
			var a = $(this).attr("href"),
				b = $(this).attr("dialog-width"),
				c = $(this).attr("dialog-height"),
				d = $(this).attr("dialog-title"),
				e = $(this).attr("dialog-on-open"),
				f = $(this).attr("dialog-on-destroy"),
				g = $(this).attr("dialog-on-close");
			return obj = {
				modal: !0,
				href: a,
				width: b,
				height: c,
				title: d,
				onOpen: e,
				onDestroy: f,
				onClose: g
			}, jQuery.fn.dialog.open(obj), !1
		})
	})
}, jQuery.fn.dialog.close = function(a) {
	a++, $("#ccm-dialog-content" + a).jqdialog("close")
}, jQuery.fn.dialog.open = function(obj) {
	jQuery.fn.dialog.showLoader(), ccm_uiLoaded && ccm_hideMenus();
	var nd = $(".ui-dialog").length;
	nd++, $("body").append('<div id="ccm-dialog-content' + nd + '" style="display: none"></div>'), "string" == typeof obj.width ? (obj.width.indexOf("%", 0) > 0 ? (w = obj.width.replace("%", ""), w = $(window).width() * (w / 100), w += 50) : w = parseInt(obj.width) + 50, obj.height.indexOf("%", 0) > 0 ? (h = obj.height.replace("%", ""), h = $(window).height() * (h / 100), h += 100) : h = parseInt(obj.height) + 100) : obj.width ? (w = parseInt(obj.width) + 50, h = parseInt(obj.height) + 100) : (w = 550, h = 400), h > $(window).height() && (h = $(window).height()), $("#ccm-dialog-content" + nd).jqdialog({
		modal: !0,
		height: h,
		width: w,
		show: {
			effect: "fade",
			duration: 150,
			easing: "easeInExpo"
		},
		escapeClose: !0,
		title: obj.title,
		open: function() {
			var a = $(".ui-dialog").length;
			1 == a && ($("body").attr("data-last-overflow", $("body").css("overflow")), $("body").css("overflow", "hidden"))
		},
		beforeClose: function() {
			var a = $(".ui-dialog").length;
			1 == a && $("body").css("overflow", $("body").attr("data-last-overflow"))
		},
		close: function(ev, u) {
			$(this).jqdialog("destroy").remove(), $("#ccm-dialog-content" + nd).remove(), "undefined" != typeof obj.onClose && ("function" == typeof obj.onClose ? obj.onClose() : eval(obj.onClose)), "undefined" != typeof obj.onDestroy && ("function" == typeof obj.onDestroy ? obj.onDestroy() : eval(obj.onDestroy)), nd--
		}
	}), obj.element ? (jQuery.fn.dialog.hideLoader(), jQuery.fn.dialog.replaceTop($(obj.element)), "undefined" != typeof obj.onOpen && ("function" == typeof obj.onOpen ? obj.onOpen() : eval(obj.onOpen))) : $.ajax({
		type: "GET",
		url: obj.href,
		success: function(r) {
			jQuery.fn.dialog.hideLoader(), jQuery.fn.dialog.replaceTop(r), "undefined" != typeof obj.onOpen && ("function" == typeof obj.onOpen ? obj.onOpen() : eval(obj.onOpen))
		}
	})
}, jQuery.fn.dialog.replaceTop = function(a) {
	var b = $(".ui-dialog").length;
	if ("string" == typeof a) $("#ccm-dialog-content" + b).html(a);
	else {
		var c = a.clone(!0, !0).appendTo("#ccm-dialog-content" + b);
		"none" == c.css("display") && c.show()
	}
	if ($("#ccm-dialog-content" + b + " .dialog-launch").dialog(), $("#ccm-dialog-content" + b + " .ccm-dialog-close").click(function(a) {
		a.preventDefault(), jQuery.fn.dialog.closeTop()
	}), $("#ccm-dialog-content" + b + " .dialog-buttons").length > 0 && ($("#ccm-dialog-content" + b).jqdialog("option", "buttons", [{}]), $("#ccm-dialog-content" + b).parent().find(".ui-dialog-buttonset").remove(), $("#ccm-dialog-content" + b).parent().find(".ui-dialog-buttonpane").html(""), $("#ccm-dialog-content" + b + " .dialog-buttons").appendTo($("#ccm-dialog-content" + b).parent().find(".ui-dialog-buttonpane").addClass("ccm-ui"))), $("#ccm-dialog-content" + b + " .dialog-help").length > 0) {
		$("#ccm-dialog-content" + b + " .dialog-help").hide();
		var d = $("#ccm-dialog-content" + b + " .dialog-help").html();
		if (ccmi18n.helpPopup) var e = ccmi18n.helpPopup;
		else var e = "Help";
		$("#ccm-dialog-content" + b).parent().find(".ui-dialog-titlebar").append('<span class="ccm-dialog-help"><a href="javascript:void(0)" title="' + e + '" class="ccm-menu-help-trigger">Help</a></span>'), $("#ccm-dialog-content" + b).parent().find(".ui-dialog-titlebar .ccm-menu-help-trigger").popover({
			content: function() {
				return d
			},
			placement: "bottom",
			html: !0,
			trigger: "click"
		})
	}
}, jQuery.fn.dialog.showLoader = function(a) {
	if ("undefined" == typeof imgLoader || !imgLoader || !imgLoader.src) return !1;
	$("#ccm-dialog-loader").length < 1 && $("body").append("<div id='ccm-dialog-loader-wrapper' class='ccm-ui'><img id='ccm-dialog-loader' src='" + imgLoader.src + "' /></div>"), null != a && $("<div />").attr("id", "ccm-dialog-loader-text").html(a).prependTo($("#ccm-dialog-loader-wrapper"));
	var b = $("#ccm-dialog-loader-wrapper").width(),
		c = $("#ccm-dialog-loader-wrapper").height(),
		d = $(window).width(),
		e = $(window).height(),
		f = (d - b) / 2,
		g = (e - c) / 2;
	$("#ccm-dialog-loader-wrapper").css("left", f + "px").css("top", g + "px"), $("#ccm-dialog-loader-wrapper").show()
}, jQuery.fn.dialog.hideLoader = function() {
	$("#ccm-dialog-loader-wrapper").hide(), $("#ccm-dialog-loader-text").remove()
}, jQuery.fn.dialog.closeTop = function() {
	var a = $(".ui-dialog").length;
	$("#ccm-dialog-content" + a).jqdialog("close")
}, jQuery.fn.dialog.closeAll = function() {
	$($(".ui-dialog-content").get().reverse()).jqdialog("close")
};
var imgLoader, ccm_dialogOpen = 0;
jQuery.fn.dialog.loaderImage = CCM_IMAGE_PATH + "/throbber_white_32.gif";
var ccmAlert = {
	notice: function(a, b, c) {
		$.fn.dialog.open({
			href: CCM_TOOLS_PATH + "/alert",
			title: a,
			width: 320,
			height: 160,
			modal: !1,
			onOpen: function() {
				$("#ccm-popup-alert-message").html(b)
			},
			onDestroy: c
		})
	},
	hud: function(a, b, c, d) {
		if (0 == $("#ccm-notification-inner").length && $(document.body).append('<div id="ccm-notification" class="ccm-ui"><div id="ccm-notification-inner"></div></div>'), null == c && (c = "edit_small"), null == d) var e = a;
		else var e = "<h3>" + d + "</h3>" + a;
		$("#ccm-notification-inner").html('<img id="ccm-notification-icon" src="' + CCM_IMAGE_PATH + "/icons/" + c + '.png" width="16" height="16" /><div id="ccm-notification-message">' + e + "</div>"), $("#ccm-notification").show(), b > 0 && setTimeout(function() {
			$("#ccm-notification").fadeOut({
				easing: "easeOutExpo",
				duration: 300
			})
		}, b)
	}
};
$(document).ready(function() {
	imgLoader = new Image, imgLoader.src = jQuery.fn.dialog.loaderImage
}), ccm_closeNewsflow = function() {
	$ovl = ccm_getNewsflowOverlayWindow(), $ovl.fadeOut(300, "easeOutExpo"), $(".ui-widget-overlay").fadeOut(300, "easeOutExpo", function() {
		$(this).remove()
	})
}, ccm_setNewsflowPagingArrowHeight = function() {
	if ($("#ccm-marketplace-detail").length > 0) var a = $("#ccm-marketplace-detail");
	else var a = $("#newsflow-main");
	var b = a.height();
	$(".newsflow-paging-previous a, .newsflow-paging-next a").css("height", b + "px"), $(".newsflow-paging-previous, .newsflow-paging-next").css("height", b + "px"), $(".newsflow-paging-next").show(), $(".newsflow-paging-previous").show()
}, ccm_setNewsflowOverlayDimensions = function() {
	if ($("#newsflow-overlay").length > 0) {
		var a = $("#newsflow-overlay").width(),
			b = $(window).width(),
			c = $(window).height(),
			d = 650,
			e = c - 80;
		h = e > d ? d : e, $("#newsflow-overlay").css("height", d);
		var f = (b - a) / 2,
			g = (c - h) / 2;
		g += 29, f += "px", g += "px", $("#newsflow-overlay").css("left", f).css("top", g)
	}
}, ccm_getNewsflowOverlayWindow = function() {
	if ($("#ccm-dashboard-content").length > 0 && $("#newsflow-main").length > 0 && 0 == $("#newsflow-overlay").length) var a = $("#newsflow-main").parent();
	else if ($("#newsflow-overlay").length > 0) var a = $("#newsflow-overlay");
	else var a = $("<div />").attr("id", "newsflow-overlay").attr("class", "ccm-ui").css("display", "none").appendTo(document.body);
	return a
}, ccm_showNewsflowOverlayWindow = function(a, b) {
	if ($("#ccm-dashboard-content").length > 0 && $("#newsflow-main").length > 0);
	else {
		if ($(".ui-widget-overlay").length < 1) {
			$('<div class="ui-widget-overlay"></div>').hide().appendTo("body")
		}
		$(".ui-widget-overlay").show()
	}
	$(window).resize(function() {
		ccm_setNewsflowOverlayDimensions()
	}), $ovl = ccm_getNewsflowOverlayWindow(), $ovl.load(a, function() {
		$ovl.hide(), $(".newsflow-paging-next").hide(), $(".newsflow-paging-previous").hide(), $ovl.html($(this).html()), b && b(), ccm_setNewsflowOverlayDimensions(), ccm_setupTrickleUpNewsflowStyles(), $ovl.fadeIn("300", "easeOutExpo", function() {
			ccm_setNewsflowPagingArrowHeight()
		})
	})
}, ccm_setupTrickleUpNewsflowStyles = function() {
	ovl = ccm_getNewsflowOverlayWindow(), ovl.find(".newsflow-em1").each(function() {
		$(this).parent().addClass("newsflow-em1")
	})
}, ccm_showDashboardNewsflowWelcome = function() {
	jQuery.fn.dialog.showLoader(ccmi18n.newsflowLoading), ccm_showNewsflowOverlayWindow(CCM_DISPATCHER_FILENAME + "/dashboard/home?_ccm_dashboard_external=1", function() {
		jQuery.fn.dialog.hideLoader()
	})
}, ccm_showNewsflowOffsite = function(a) {
	jQuery.fn.dialog.showLoader(), ccm_showNewsflowOverlayWindow(CCM_TOOLS_PATH + "/newsflow?cID=" + a, function() {
		jQuery.fn.dialog.hideLoader()
	})
}, ccm_showAppIntroduction = function() {
	ccm_showNewsflowOverlayWindow(CCM_DISPATCHER_FILENAME + "/dashboard/welcome?_ccm_dashboard_external=1")
}, ccm_getNewsflowByPath = function(a) {
	jQuery.fn.dialog.showLoader(), ccm_showNewsflowOverlayWindow(CCM_TOOLS_PATH + "/newsflow?cPath=" + a, function() {
		jQuery.fn.dialog.hideLoader()
	})
}, ccm_doPageReindexing = function() {
	$.get(CCM_TOOLS_PATH + "/reindex_pending_pages?ccm_token=" + CCM_SECURITY_TOKEN)
}, String.prototype.score = function(a, b) {
	if (b = b || 0, 0 == a.length) return .9;
	if (a.length > this.length) return 0;
	for (var c = a.length; c > 0; c--) {
		var d = a.substring(0, c),
			e = this.indexOf(d);
		if (!(0 > e || e + a.length > this.length + b)) {
			var f = this.substring(e + d.length),
				g = null;
			g = c >= a.length ? "" : a.substring(c);
			var h = f.score(g, b + e);
			if (h > 0) {
				var i = this.length - f.length;
				if (0 != e) {
					var j = 0,
						k = this.charCodeAt(e - 1);
					if (32 == k || 9 == k) for (var j = e - 2; j >= 0; j--) k = this.charCodeAt(j), i -= 32 == k || 9 == k ? 1 : .15;
					else i -= e
				}
				return i += h * f.length, i /= this.length
			}
		}
	}
	return 0
}, ccm_openThemeLauncher = function() {
	jQuery.fn.dialog.closeTop(), jQuery.fn.dialog.showLoader(), ccm_testMarketplaceConnection(function() {
		$.fn.dialog.open({
			title: ccmi18n.community,
			href: CCM_TOOLS_PATH + "/marketplace/themes",
			width: "905",
			modal: !1,
			height: "410"
		})
	}, "open_theme_launcher")
}, ccm_testMarketplaceConnection = function(a, b, c) {
	mpIDStr = c ? "&mpID=" + c : "", b || (b = ""), params = {
		mpID: c
	}, $.getJSON(CCM_TOOLS_PATH + "/marketplace/connect", params, function(c) {
		return c.isConnected ? void a() : ($.fn.dialog.open({
			title: ccmi18n.community,
			href: CCM_TOOLS_PATH + "/marketplace/frame?task=" + b + mpIDStr,
			width: "90%",
			modal: !1,
			height: "70%"
		}), !1)
	})
}, ccm_openAddonLauncher = function() {
	jQuery.fn.dialog.closeTop(), jQuery.fn.dialog.showLoader(), ccm_testMarketplaceConnection(function() {
		$.fn.dialog.open({
			title: ccmi18n.community,
			href: CCM_TOOLS_PATH + "/marketplace/add-ons",
			width: "905",
			modal: !1,
			height: "410"
		})
	}, "open_addon_launcher")
}, ccm_setupMarketplaceDialogForm = function() {
	$(".ccm-pane-dialog-pagination").each(function() {
		$(this).closest(".ui-dialog-content").dialog("option", "buttons", [{}]), $(this).closest(".ui-dialog").find(".ui-dialog-buttonpane .ccm-pane-dialog-pagination").remove(), $(this).appendTo($(this).closest(".ui-dialog").find(".ui-dialog-buttonpane").addClass("ccm-ui"))
	}), $(".ccm-pane-dialog-pagination a").click(function() {
		return jQuery.fn.dialog.showLoader(), $("#ccm-marketplace-browser-form").closest(".ui-dialog-content").load($(this).attr("href"), function() {
			jQuery.fn.dialog.hideLoader()
		}), !1
	}), ccm_marketplaceBrowserInit(), $("#ccm-marketplace-browser-form").ajaxForm({
		beforeSubmit: function() {
			jQuery.fn.dialog.showLoader()
		},
		success: function(a) {
			jQuery.fn.dialog.hideLoader(), $("#ccm-marketplace-browser-form").closest(".ui-dialog-content").html(a)
		}
	})
}, ccm_marketplaceBrowserInit = function() {
	$(".ccm-marketplace-item").click(function() {
		ccm_getMarketplaceItemDetails($(this).attr("mpID"))
	}), $(".ccm-marketplace-item-thumbnail").mouseover(function() {
		var a = $(this).parent().find("div.ccm-marketplace-results-image-hover").clone().addClass("ccm-marketplace-results-image-hover-displayed").appendTo(document.body),
			b = $(this).offset().top,
			c = $(this).offset().left;
		c += 60, a.css("top", b).css("left", c), a.show()
	}), $(".ccm-marketplace-item-thumbnail").mouseout(function() {
		$(".ccm-marketplace-results-image-hover-displayed").hide().remove()
	})
}, ccm_getMarketplaceItemDetails = function(a) {
	jQuery.fn.dialog.showLoader(), $("#ccm-intelligent-search-results").hide(), ccm_testMarketplaceConnection(function() {
		$.fn.dialog.open({
			title: ccmi18n.community,
			href: CCM_TOOLS_PATH + "/marketplace/details?mpID=" + a,
			width: 820,
			appendButtons: !0,
			modal: !1,
			height: 640
		})
	}, "get_item_details", a)
}, ccm_getMarketplaceItem = function(a) {
	var b = a.mpID,
		c = a.closeTop;
	this.onComplete = function() {}, a.onComplete && (ccm_getMarketplaceItem.onComplete = a.onComplete), c && jQuery.fn.dialog.closeTop(), jQuery.fn.dialog.showLoader(), params = {
		mpID: b
	}, $.getJSON(CCM_TOOLS_PATH + "/marketplace/connect", params, function(a) {
		jQuery.fn.dialog.hideLoader(), $.fn.dialog.open(a.isConnected ? a.purchaseRequired ? {
			title: ccmi18n.communityCheckout,
			iframe: !0,
			href: CCM_TOOLS_PATH + "/marketplace/checkout?mpID=" + b,
			width: "560px",
			modal: !1,
			height: "400px"
		} : {
			title: ccmi18n.community,
			href: CCM_TOOLS_PATH + "/marketplace/download?install=1&mpID=" + b,
			width: 500,
			appendButtons: !0,
			modal: !1,
			height: 400
		} : {
			title: ccmi18n.community,
			href: CCM_TOOLS_PATH + "/marketplace/frame?task=get&mpID=" + b,
			width: "90%",
			modal: !1,
			height: "70%"
		})
	})
};
var ccm_searchActivatePostFunction = new Array;
ccm_setupAdvancedSearchFields = function(a) {
	ccm_totalAdvancedSearchFields = $(".ccm-search-request-field-set").length, $("#ccm-" + a + "-search-add-option").unbind(), $("#ccm-" + a + "-search-add-option").click(function() {
		ccm_totalAdvancedSearchFields++, $("#ccm-search-fields-wrapper").length > 0 ? $("#ccm-search-fields-wrapper").append('<div class="ccm-search-field" id="ccm-' + a + "-search-field-set" + ccm_totalAdvancedSearchFields + '">' + $("#ccm-search-field-base").html() + "</div>") : $("#ccm-" + a + "-search-advanced-fields").append('<tr class="ccm-search-field" id="ccm-' + a + "-search-field-set" + ccm_totalAdvancedSearchFields + '">' + $("#ccm-search-field-base").html() + "</tr>"), ccm_activateAdvancedSearchFields(a, ccm_totalAdvancedSearchFields)
	});
	var b = 1;
	$(".ccm-search-request-field-set").each(function() {
		ccm_activateAdvancedSearchFields(a, b), b++
	})
}, ccm_setupAdvancedSearch = function(a) {
	ccm_setupAdvancedSearchFields(a), $("#ccm-" + a + "-advanced-search").ajaxForm({
		beforeSubmit: function() {
			ccm_deactivateSearchResults(a)
		},
		success: function(b) {
			ccm_parseAdvancedSearchResponse(b, a)
		}
	}), ccm_setupInPagePaginationAndSorting(a), ccm_setupSortableColumnSelection(a)
}, ccm_parseAdvancedSearchResponse = function(a, b) {
	var c = $("#ccm-" + b + "-search-results");
	(0 == c.length || null == b) && (c = $("#ccm-search-results")), c.html(a), ccm_activateSearchResults(b)
}, ccm_deactivateSearchResults = function(a) {
	var b = $("#ccm-" + a + "-search-fields-submit");
	(0 == b.length || null == a) && (b = $("#ccm-search-fields-submit")), b.attr("disabled", !0);
	var b = $("#ccm-" + a + "-search-results table.ccm-results-list");
	(0 == b.length || null == a) && (b = $("#ccm-search-results")), b.css("opacity", .4), jQuery.fn.dialog.showLoader()
}, ccm_activateSearchResults = function(a) {
	0 == $(".ui-dialog-content").length ? window.scrollTo(0, 0) : $(".ui-dialog-content").each(function() {
		$(this).get(0).scrollTop = 0
	}), $(".dialog-launch").dialog();
	var b = $("#ccm-" + a + "-search-results table.ccm-results-list");
	(0 == b.length || null == a) && (b = $("#ccm-search-results")), b.css("opacity", 1), jQuery.fn.dialog.hideLoader();
	var b = $("#ccm-" + a + "-search-fields-submit");
	(0 == b.length || null == a) && (b = $("#ccm-search-fields-submit")), b.attr("disabled", !1), ccm_setupInPagePaginationAndSorting(a), ccm_setupSortableColumnSelection(a), "function" == typeof ccm_searchActivatePostFunction[a] && ccm_searchActivatePostFunction[a]()
}, ccm_setupInPagePaginationAndSorting = function(a) {
	$(".ccm-results-list th a").click(function() {
		ccm_deactivateSearchResults(a);
		var b = $("#ccm-" + a + "-search-results");
		return 0 == b.length && (b = $("#ccm-search-results")), b.load($(this).attr("href"), !1, function() {
			ccm_activateSearchResults(a)
		}), !1
	}), $("div.ccm-pagination a").click(function() {
		if (!$(this).parent().hasClass("disabled")) {
			ccm_deactivateSearchResults(a);
			var b = $("#ccm-" + a + "-search-results");
			0 == b.length && (b = $("#ccm-search-results")), b.load($(this).attr("href"), !1, function() {
				ccm_activateSearchResults(a), $("div.ccm-dialog-content").attr("scrollTop", 0)
			})
		}
		return !1
	}), $(".ccm-pane-dialog-pagination").each(function() {
		$(this).closest(".ui-dialog-content").dialog("option", "buttons", [{}]), $(this).closest(".ui-dialog").find(".ui-dialog-buttonpane .ccm-pane-dialog-pagination").remove(), $(this).appendTo($(this).closest(".ui-dialog").find(".ui-dialog-buttonpane").addClass("ccm-ui"))
	})
}, ccm_setupSortableColumnSelection = function() {
	$("#ccm-list-view-customize").unbind(), $("#ccm-list-view-customize").click(function() {
		return jQuery.fn.dialog.open({
			width: 550,
			height: 350,
			appendButtons: !0,
			modal: !1,
			href: $(this).attr("href"),
			title: ccmi18n.customizeSearch
		}), !1
	})
}, ccm_checkSelectedAdvancedSearchField = function(a, b) {
	$("#ccm-" + a + "-search-field-set" + b + " .ccm-search-option-type-date_time input").each(function() {
		$(this).attr("id", $(this).attr("id") + b)
	}), $("#ccm-" + a + "-search-field-set" + b + " .ccm-search-option-type-date_time input").datepicker({
		showAnim: "fadeIn"
	}), $("#ccm-" + a + "-search-field-set" + b + " .ccm-search-option-type-rating input").rating()
}, ccm_activateAdvancedSearchFields = function(a, b) {
	var c = $("#ccm-" + a + "-search-field-set" + b + " select:first");
	c.unbind(), c.change(function() {
		var c = $(this).find(":selected").val();
		$(this).parent().parent().find("input.ccm-" + a + "-selected-field").val(c);
		var d = $("#ccm-" + a + "-search-field-base-elements span[search-field=" + c + "]");
		$("#ccm-" + a + "-search-field-set" + b + " .ccm-selected-field-content").html(""), d.clone().appendTo("#ccm-" + a + "-search-field-set" + b + " .ccm-selected-field-content"), $("#ccm-" + a + "-search-field-set" + b + " .ccm-selected-field-content .ccm-search-option").show(), ccm_checkSelectedAdvancedSearchField(a, b)
	}), $("#ccm-" + a + "-search-field-set" + b + " .ccm-search-remove-option").unbind(), $("#ccm-" + a + "-search-field-set" + b + " .ccm-search-remove-option").click(function() {
		$(this).parents("div.ccm-search-field").remove(), $(this).parents("tr.ccm-search-field").remove()
	}), ccm_checkSelectedAdvancedSearchField(a, b)
}, ccm_activateEditablePropertiesGrid = function() {
	$("tr.ccm-attribute-editable-field").each(function() {
		var a = $(this);
		$(this).find("a").click(function() {
			a.find(".ccm-attribute-editable-field-text").hide(), a.find(".ccm-attribute-editable-field-clear-button").hide(), a.find(".ccm-attribute-editable-field-form").show(), a.find(".ccm-attribute-editable-field-save-button").show()
		}), a.find("form").submit(function() {
			return !1
		}), a.find(".ccm-attribute-editable-field-save-button").parent().click(function() {
			var b = a.find("form input[name=task]");
			"clear_extended_attribute" == b.val() && (b.val(b.attr("data-original-task")), b.attr("data-original-task", "")), ccm_submitEditablePropertiesGrid(a)
		}), a.find(".ccm-attribute-editable-field-clear-button").parent().unbind(), a.find(".ccm-attribute-editable-field-clear-button").parent().click(function() {
			var b = a.find("form input[name=task]");
			return b.attr("data-original-task", b.val()), b.val("clear_extended_attribute"), ccm_submitEditablePropertiesGrid(a), !1
		})
	})
}, ccm_submitEditablePropertiesGrid = function(a) {
	a.find(".ccm-attribute-editable-field-save-button").hide(), a.find(".ccm-attribute-editable-field-clear-button").hide(), a.find(".ccm-attribute-editable-field-loading").show();
	try {
		tinyMCE.triggerSave(!0, !0)
	} catch (b) {}
	a.find("form").ajaxSubmit(function(b) {
		a.find(".ccm-attribute-editable-field-loading").hide(), a.find(".ccm-attribute-editable-field-save-button").show(), a.find(".ccm-attribute-editable-field-text").html(b), a.find(".ccm-attribute-editable-field-form").hide(), a.find(".ccm-attribute-editable-field-save-button").hide(), a.find(".ccm-attribute-editable-field-text").show(), a.find(".ccm-attribute-editable-field-clear-button").show(), a.find("td").show("highlight", {
			color: "#FFF9BB"
		})
	})
};
var tr_activeNode = !1;
if ("undefined" == typeof tr_doAnim) var tr_doAnim = !1;
var tr_parseSubnodes = !0,
	tr_reorderMode = !1,
	tr_moveCopyMode = !1;
showPageMenu = function(a, b) {
	ccm_hideMenus(), b.stopPropagation();
	var c = $("#ccm-page-menu" + a.cID);
	if (c.get(0)) c = $("#ccm-page-menu" + a.cID);
	else {
		el = document.createElement("DIV"), el.id = "ccm-page-menu" + a.cID, el.className = "ccm-menu ccm-ui", el.style.display = "block", el.style.visibility = "hidden", document.body.appendChild(el), c = $("#ccm-page-menu" + a.cID), c.css("position", "absolute");
		var d = '<div class="popover"><div class="arrow"></div><div class="inner"><div class="content">';
		if (d += "<ul>", a.isTrash) d += '<li><a class="ccm-menu-icon ccm-icon-delete-menu" onclick="ccm_sitemapDeleteForever(' + a.instance_id + "," + a.cID + ', true)" href="javascript:void(0)">' + ccmi18n_sitemap.emptyTrash + "</a></li>";
		else if (a.inTrash) d += '<li><a class="ccm-menu-icon ccm-icon-search-pages" onclick="ccm_previewInternalTheme(' + a.cID + ", false, '" + ccmi18n_sitemap.previewPage + '\')" href="javascript:void(0)">' + ccmi18n_sitemap.previewPage + "</a></li>", d += '<li class="ccm-menu-separator"></li>', d += '<li><a class="ccm-menu-icon ccm-icon-delete-menu" onclick="ccm_sitemapDeleteForever(' + a.instance_id + "," + a.cID + ', false)" href="javascript:void(0)">' + ccmi18n_sitemap.deletePageForever + "</a></li>";
		else if ("LINK" == a.cAlias || "POINTER" == a.cAlias) d += '<li><a class="ccm-menu-icon ccm-icon-visit" id="menuVisit' + a.cID + '" href="javascript:void(0)" onclick="window.location.href=\'' + CCM_DISPATCHER_FILENAME + "?cID=" + a.cID + "'\">" + ccmi18n_sitemap.visitExternalLink + "</a></li>", "LINK" == a.cAlias && a.canEditProperties && (d += '<li><a class="ccm-menu-icon ccm-icon-edit-external-link" dialog-width="350" dialog-height="170" dialog-title="' + ccmi18n_sitemap.editExternalLink + '" dialog-modal="false" dialog-append-buttons="true" id="menuLink' + a.cID + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&cID=" + a.cID + '&ctask=edit_external">' + ccmi18n_sitemap.editExternalLink + "</a></li>"), a.canDelete && (d += '<li><a class="ccm-menu-icon ccm-icon-delete-menu" dialog-append-buttons="true" id="menuDelete' + a.cID + '" dialog-width="360" dialog-height="150" dialog-modal="false" dialog-append-buttons="true" dialog-title="' + ccmi18n_sitemap.deleteExternalLink + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&cID=" + a.cID + "&display_mode=" + a.display_mode + "&instance_id=" + a.instance_id + "&select_mode=" + a.select_mode + '&ctask=delete_external">' + ccmi18n_sitemap.deleteExternalLink + "</a></li>");
		else {
			if (d += '<li><a class="ccm-menu-icon ccm-icon-visit" id="menuVisit' + a.cID + '" href="' + CCM_DISPATCHER_FILENAME + "?cID=" + a.cID + '">' + ccmi18n_sitemap.visitPage + "</a></li>", a.canCompose && (d += '<li><a class="ccm-menu-icon ccm-icon-edit-in-composer-menu" id="menuComposer' + a.cID + '" href="' + CCM_DISPATCHER_FILENAME + "/dashboard/composer/write/-/edit/" + a.cID + '">' + ccmi18n_sitemap.editInComposer + "</a></li>"), (a.canEditProperties || a.canEditSpeedSettings || a.canEditPermissions || a.canEditDesign || a.canViewVersions || a.canDelete) && (d += '<li class="ccm-menu-separator"></li>'), a.canEditProperties && (d += '<li><a class="ccm-menu-icon ccm-icon-properties-menu" dialog-on-close="ccm_sitemapExitEditMode(' + a.cID + ')" dialog-width="670" dialog-height="360" dialog-append-buttons="true" dialog-modal="false" dialog-title="' + ccmi18n_sitemap.pagePropertiesTitle + '" id="menuProperties' + a.cID + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&cID=" + a.cID + '&ctask=edit_metadata">' + ccmi18n_sitemap.pageProperties + "</a></li>"), a.canEditSpeedSettings && (d += '<li><a class="ccm-menu-icon ccm-icon-speed-settings-menu" dialog-on-close="ccm_sitemapExitEditMode(' + a.cID + ')" dialog-width="550" dialog-height="280" dialog-append-buttons="true" dialog-modal="false" dialog-title="' + ccmi18n_sitemap.speedSettingsTitle + '" id="menuSpeedSettings' + a.cID + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&cID=" + a.cID + '&ctask=edit_speed_settings">' + ccmi18n_sitemap.speedSettings + "</a></li>"), a.canEditPermissions && (d += '<li><a class="ccm-menu-icon ccm-icon-permissions-menu" dialog-on-close="ccm_sitemapExitEditMode(' + a.cID + ')" dialog-width="420" dialog-height="630" dialog-append-buttons="true" dialog-modal="false" dialog-title="' + ccmi18n_sitemap.setPagePermissions + '" id="menuPermissions' + a.cID + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&cID=" + a.cID + '&ctask=edit_permissions">' + ccmi18n_sitemap.setPagePermissions + "</a></li>"), a.canEditDesign && (d += '<li><a class="ccm-menu-icon ccm-icon-design-menu" dialog-on-close="ccm_sitemapExitEditMode(' + a.cID + ')" dialog-width="610" dialog-append-buttons="true" dialog-height="405" dialog-modal="false" dialog-title="' + ccmi18n_sitemap.pageDesign + '" id="menuDesign' + a.cID + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&cID=" + a.cID + '&ctask=set_theme">' + ccmi18n_sitemap.pageDesign + "</a></li>"), a.canViewVersions && (d += '<li><a class="ccm-menu-icon ccm-icon-versions-menu" dialog-on-close="ccm_sitemapExitEditMode(' + a.cID + ')" dialog-width="640" dialog-height="340" dialog-modal="false" dialog-title="' + ccmi18n_sitemap.pageVersions + '" id="menuVersions' + a.cID + '" href="' + CCM_TOOLS_PATH + "/versions.php?rel=SITEMAP&cID=" + a.cID + '">' + ccmi18n_sitemap.pageVersions + "</a></li>"), a.canDelete && (d += '<li><a class="ccm-menu-icon ccm-icon-delete-menu" dialog-on-close="ccm_sitemapExitEditMode(' + a.cID + ')" dialog-append-buttons="true" id="menuDelete' + a.cID + '" dialog-width="360" dialog-height="200" dialog-modal="false" dialog-title="' + ccmi18n_sitemap.deletePage + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&cID=" + a.cID + "&display_mode=" + a.display_mode + "&instance_id=" + a.instance_id + "&select_mode=" + a.select_mode + '&ctask=delete">' + ccmi18n_sitemap.deletePage + "</a></li>"), ("explore" == a.display_mode || "search" == a.display_mode) && (d += '<li class="ccm-menu-separator"></li>', d += '<li><a class="ccm-menu-icon ccm-icon-move-copy-menu" dialog-width="90%" dialog-height="70%" dialog-modal="false" dialog-title="' + ccmi18n_sitemap.moveCopyPage + '" id="menuMoveCopy' + a.cID + '" href="' + CCM_TOOLS_PATH + "/sitemap_search_selector?sitemap_select_mode=move_copy_delete&cID=" + a.cID + '" id="menuMoveCopy' + a.cID + '">' + ccmi18n_sitemap.moveCopyPage + "</a></li>", "explore" == a.display_mode && (d += '<li><a class="ccm-menu-icon ccm-icon-move-up" id="menuSendToStop' + a.cID + '" href="' + CCM_DISPATCHER_FILENAME + "/dashboard/sitemap/explore?cNodeID=" + a.cID + '&task=send_to_top">' + ccmi18n_sitemap.sendToTop + "</a></li>", d += '<li><a class="ccm-menu-icon ccm-icon-move-down" id="menuSendToBottom' + a.cID + '" href="' + CCM_DISPATCHER_FILENAME + "/dashboard/sitemap/explore?cNodeID=" + a.cID + '&task=send_to_bottom">' + ccmi18n_sitemap.sendToBottom + "</a></li>")), a.cNumChildren > 0) {
				d += '<li class="ccm-menu-separator"></li>';
				var e = CCM_DISPATCHER_FILENAME + "/dashboard/sitemap/search/?selectedSearchField[]=parent&cParentAll=1&cParentIDSearchField=" + a.cID;
				("full" == a.display_mode || "" == a.display_mode || "explore" == a.display_mode) && (d += '<li><a class="ccm-menu-icon ccm-icon-search-pages" id="menuSearch' + a.cID + '" href="' + e + '">' + ccmi18n_sitemap.searchPages + "</a></li>"), "explore" != a.display_mode && (d += '<li><a class="ccm-menu-icon ccm-icon-flat-view" id="menuExplore' + a.cID + '" href="' + CCM_DISPATCHER_FILENAME + "/dashboard/sitemap/explore/-/" + a.cID + '">' + ccmi18n_sitemap.explorePages + "</a></li>")
			}(a.canAddSubpages || a.canAddExternalLinks) && (d += '<li class="ccm-menu-separator"></li>'), a.canAddSubpages && (d += '<li><a class="ccm-menu-icon ccm-icon-add-page-menu" dialog-append-buttons="true" dialog-width="645" dialog-modal="false" dialog-height="345" dialog-title="' + ccmi18n_sitemap.addPage + '" id="menuSubPage' + a.cID + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&mode=" + a.display_mode + "&cID=" + a.cID + '&ctask=add">' + ccmi18n_sitemap.addPage + "</a></li>"), "search" != a.display_mode && a.canAddExternalLinks && (d += '<li><a class="ccm-menu-icon ccm-icon-add-external-link-menu" dialog-width="350" dialog-modal="false" dialog-height="170" dialog-title="' + ccmi18n_sitemap.addExternalLink + '" dialog-modal="false" dialog-append-buttons="true" id="menuLink' + a.cID + '" href="' + CCM_TOOLS_PATH + "/edit_collection_popup.php?rel=SITEMAP&cID=" + a.cID + '&ctask=add_external">' + ccmi18n_sitemap.addExternalLink + "</a></li>")
		}
		d += "</ul>", d += "</div></div></div>", c.append(d), $(c).find("a").bind("click.hide-menu", function() {
			ccm_hideMenus()
		}), $("#menuProperties" + a.cID).dialog(), $("#menuSpeedSettings" + a.cID).dialog(), $("#menuSubPage" + a.cID).dialog(), $("#menuDesign" + a.cID).dialog(), $("#menuLink" + a.cID).dialog(), $("#menuVersions" + a.cID).dialog(), $("#menuPermissions" + a.cID).dialog(), $("#menuMoveCopy" + a.cID).dialog(), $("#menuDelete" + a.cID).dialog()
	}
	ccm_fadeInMenu(c, b)
}, hideBranch = function(a) {
	$("#tree-node" + a).hide(), $("#tree-dz" + a).hide()
}, cancelReorder = function() {
	tr_reorderMode && (tr_reorderMode = !1, $("li.tree-node[draggable=true]").draggable("destroy"), tr_moveCopyMode || hideSitemapMessage())
}, ccm_sitemapExitEditMode = function(a) {
	$.get(CCM_TOOLS_PATH + "/dashboard/sitemap_check_in?cID=" + a + "&ccm_token=" + CCM_SECURITY_TOKEN)
}, searchSubPages = function(a) {
	$("#ccm-tree-search-trigger" + a).hide(), ccm_animEffects ? $("#ccm-tree-search" + a).fadeIn(200, function() {
		$("#ccm-tree-search" + a + " input").get(0).focus()
	}) : ($("#ccm-tree-search" + a).show(), $("#ccm-tree-search" + a + " input").get(0).focus())
}, activateReorder = function() {
	tr_reorderMode = !0, $("li.tree-node[draggable=true]").draggable({
		handle: "img.handle",
		opacity: .5,
		revert: !1,
		helper: "clone",
		start: function() {
			$(document.body).css("overflowX", "hidden")
		},
		stop: function() {
			$(document.body).css("overflowX", "auto")
		}
	}), fixResortingDroppables()
}, deleteBranchFade = function(a) {
	ccm_animEffects ? ($("#tree-node" + a).fadeOut(300, function() {
		$("#tree-node" + a).remove()
	}), $("#tree-dz" + a).fadeOut(300, function() {
		$("#tree-dz" + a).remove()
	})) : deleteBranchDirect(a)
}, deleteBranchDirect = function(a) {
	$("#tree-node" + a).remove(), $("#tree-dz" + a).remove()
}, showBranch = function(a) {
	$("#tree-node" + a);
	$("#tree-node" + a).show(), $("#tree-dz" + a).show()
}, rescanDisplayOrder = function(a) {
	setLoading(a);
	var b = "?foo=1",
		c = $("#tree-root" + a).children("li.tree-node");
	for (i = 0; i < c.length; i++) $(c[i]).hasClass("ui-draggable-dragging") || (b += "&cID[]=" + $(c[i]).attr("id").substring(9));
	$.getJSON(CCM_TOOLS_PATH + "/dashboard/sitemap_update.php", b, function(b) {
		ccm_parseJSON(b, function() {}), removeLoading(a)
	})
};
var SITEMAP_LAST_DIALOGUE_URL = "",
	ccm_sitemap_html = "";
parseSitemapResponse = function(a, b, c, d, e) {
	var f = $("ul[tree-root-node-id=" + d + "][sitemap-instance-id=" + a + "]");
	f.html(e), f.slideDown(150, "easeOutExpo")
}, selectMoveCopyTarget = function(a, b, c, d, e) {
	if (!e) var e = CCM_CID;
	var f = ccmi18n_sitemap.moveCopyPage,
		g = CCM_TOOLS_PATH + "/dashboard/sitemap_drag_request.php?instance_id=" + a + "&display_mode=" + b + "&select_mode=" + c + "&origCID=" + e + "&destCID=" + d,
		h = 350,
		i = 350;
	try {
		if ("<none>" == CCM_NODE_ACTION) return "" != CCM_TARGET_ID && $("#" + CCM_TARGET_ID).val(d), void $.fn.dialog.closeTop();
		"" != CCM_NODE_ACTION && (g = CCM_NODE_ACTION + "?destCID=" + d), "" != CCM_DIALOG_TITLE && (f = CCM_DIALOG_TITLE), "" != CCM_DIALOG_HEIGHT && (h = CCM_DIALOG_HEIGHT), "" != CCM_DIALOG_WIDTH && (i = CCM_DIALOG_WIDTH)
	} catch (j) {}
	$.fn.dialog.open({
		title: f,
		href: g,
		width: i,
		appendButtons: !0,
		modal: !1,
		height: h,
		onClose: function() {
			"undefined" != typeof CCM_TARGET_ID && "" != CCM_TARGET_ID && $("#" + CCM_TARGET_ID).val(d), 1 == tr_moveCopyMode && deactivateMoveCopy()
		}
	})
}, selectLabel = function(e, node) {
	var cNumChildren = node.attr("tree-node-children");
	if ("move_copy_delete" == node.attr("sitemap-select-mode") || 1 == tr_moveCopyMode) {
		var destCID = node.attr("id").substring(10),
			origCID = node.attr("selected-page-id");
		selectMoveCopyTarget(node.attr("sitemap-instance-id"), node.attr("sitemap-display-mode"), node.attr("sitemap-select-mode"), destCID, origCID)
	} else if ("select_page" == node.attr("sitemap-select-mode")) {
		var callback = node.parents("[sitemap-wrapper=1]").attr("sitemap-select-callback");
		(null == callback || "" == callback || "undefined" == typeof callback) && (callback = "ccm_selectSitemapNode"), eval(callback + "(node.attr('id').substring(10), unescape(node.attr('tree-node-title')));"), jQuery.fn.dialog.closeTop()
	} else node.addClass("tree-label-selected"), 0 != tr_activeNode && tr_activeNode.attr("id") != node.attr("id") && tr_activeNode.removeClass("tree-label-selected"), params = {
		cID: node.attr("id").substring(10),
		display_mode: node.attr("sitemap-display-mode"),
		isTrash: node.attr("tree-node-istrash"),
		inTrash: node.attr("tree-node-intrash"),
		select_mode: node.attr("sitemap-select-mode"),
		instance_id: node.attr("sitemap-instance-id"),
		canCompose: node.attr("tree-node-cancompose"),
		canEditProperties: node.attr("tree-node-can-edit-properties"),
		canEditSpeedSettings: node.attr("tree-node-can-edit-speed-settings"),
		canEditPermissions: node.attr("tree-node-can-edit-permissions"),
		canEditDesign: node.attr("tree-node-can-edit-design"),
		canViewVersions: node.attr("tree-node-can-view-versions"),
		canDelete: node.attr("tree-node-can-delete"),
		canAddSubpages: node.attr("tree-node-can-add-subpages"),
		canAddExternalLinks: node.attr("tree-node-can-add-external-links"),
		cNumChildren: node.attr("tree-node-children"),
		cAlias: node.attr("tree-node-alias")
	}, showPageMenu(params, e), tr_activeNode = node
}, ccmSitemapHighlightPageLabel = function(a, b) {
	var c = $("#tree-label" + a + " > span");
	if (0 == c.length) {
		var c = $("tr.ccm-list-record[cID=" + a + "]");
		c.length > 0 && $("#ccm-page-advanced-search").submit()
	} else null != b && c.html(b);
	c.show("highlight")
}, activateLabels = function(a, b, c) {
	var d = $("ul[sitemap-instance-id=" + a + "]");
	d.find("div.tree-label span").unbind(), d.find("div.tree-label span").click(function(a) {
		selectLabel(a, $(this).parent())
	}), d.find("ul[tree-root-state=closed]").each(function() {
		var a = $(this),
			b = $(this).attr("tree-root-node-id");
		$(this).find("li").length > 0 && (a.attr("tree-root-state", "open"), $("#tree-collapse" + b).attr("src", CCM_IMAGE_PATH + "/dashboard/minus.jpg"))
	}), ("select_page" == c || "move_copy_delete" == c) && d.find("li.ccm-sitemap-explore-paging a").each(function() {
		$(this).click(function() {
			var d = $(this).parentsUntil("ul").parent().parentsUntil("ul").parent().attr("tree-root-node-id");
			return jQuery.fn.dialog.showLoader(), $.get($(this).attr("href"), function(e) {
				parseSitemapResponse(a, b, c, d, e), activateLabels(a, b, c), jQuery.fn.dialog.hideLoader()
			}), !1
		})
	}), "explore" != b && "full" != b || c || d.find("img.handle").addClass("moveable"), "full" != b || c || (d.find("div.tree-label").droppable({
		accept: ".tree-node",
		hoverClass: "on-drop",
		drop: function(b, c) {
			var d = c.draggable,
				e = $(this).attr("id").substring(10),
				f = $(d).attr("id").substring(9);
			if (e == f) return !1;
			var g = CCM_TOOLS_PATH + "/dashboard/sitemap_drag_request.php?instance_id=" + a + "&origCID=" + f + "&destCID=" + e;
			return SITEMAP_LAST_DIALOGUE_URL == g ? !1 : (SITEMAP_LAST_DIALOGUE_URL = g, void $.fn.dialog.open({
				title: ccmi18n_sitemap.moveCopyPage,
				href: g,
				width: 350,
				modal: !1,
				height: 350,
				appendButtons: !0,
				onClose: function() {
					showBranch(f)
				}
			}))
		}
	}), d.find("li.tree-node[draggable=true]").draggable({
		handle: "img.handle",
		opacity: .5,
		revert: !1,
		helper: "clone",
		start: function() {
			$(document.body).css("overflowX", "hidden")
		},
		stop: function() {
			$(document.body).css("overflowX", "auto")
		}
	}))
}, ccm_triggerProgressiveOperation = function(a, b, c, d, e) {
	jQuery.fn.dialog.showLoader(), $("#ccm-dialog-progress-bar").remove(), $.ajax({
		url: a,
		type: "POST",
		data: b,
		success: function(f) {
			jQuery.fn.dialog.hideLoader(), $('<div id="ccm-dialog-progress-bar" />').appendTo(document.body).html(f).jqdialog({
				autoOpen: !1,
				height: 200,
				width: 400,
				modal: !0,
				title: c,
				closeOnEscape: !1,
				open: function() {
					$(".ui-dialog-titlebar-close", this.parentNode).hide();
					var c = $("#ccm-progressive-operation-progress-bar").attr("data-total-items");
					b.push({
						name: "process",
						value: "1"
					}), ccm_doProgressiveOperation(a, b, c, d, e)
				}
			}), $("#ccm-dialog-progress-bar").jqdialog("open")
		}
	})
}, ccm_doProgressiveOperation = function(a, b, c, d, e) {
	$.ajax({
		url: a,
		dataType: "json",
		type: "POST",
		data: b,
		error: function(a, b) {
			switch (b) {
			case "timeout":
				var c = ccmi18n.requestTimeout;
				break;
			default:
				var c = a.responseText
			}
			$("#ccm-dialog-progress-bar").dialog("option", "height", 200), $("#ccm-dialog-progress-bar").dialog("option", "closeOnEscape", !0), $("#ccm-progressive-operation-progress-bar").html('<div class="alert alert-error">' + c + "</div>"), $(".ui-dialog-titlebar-close").show()
		},
		success: function(f) {
			if (f.error) {
				var g = f.message;
				$("#ccm-dialog-progress-bar").dialog("option", "height", 200), $("#ccm-dialog-progress-bar").dialog("option", "closeOnEscape", !0), $("#ccm-progressive-operation-progress-bar").html('<div class="alert alert-error">' + g + "</div>"), $(".ui-dialog-titlebar-close").show(), "function" == typeof e && e(f)
			} else {
				var h = f.totalItems,
					i = Math.round((c - h) / c * 100);
				$("#ccm-progressive-operation-status").html(1), c - h > 0 && $("#ccm-progressive-operation-status").html(c - h), $("#ccm-progressive-operation-progress-bar div.bar").width(i + "%"), h > 0 ? setTimeout(function() {
					ccm_doProgressiveOperation(a, b, c, d, e)
				}, 250) : setTimeout(function() {
					$("#ccm-progressive-operation-progress-bar div.bar").width("0%"), $("#ccm-dialog-progress-bar").dialog("close"), "function" == typeof d && d(f)
				}, 1e3)
			}
		}
	})
}, ccm_refreshCopyOperations = function() {
	var a = ccmi18n_sitemap.copyProgressTitle;
	ccm_triggerProgressiveOperation(CCM_TOOLS_PATH + "/dashboard/sitemap_copy_all", [], a, function() {
		$(".ui-dialog-content").dialog("close"), window.location.reload()
	})
}, moveCopyAliasNode = function(a) {
	var b = $("#origCID").val(),
		c = $("#destParentID").val(),
		d = $("#destCID").val(),
		e = $("input[name=ctask]:checked").val(),
		f = $("input[name=instance_id]").val(),
		g = $("input[name=display_mode]").val(),
		h = $("input[name=select_mode]").val(),
		i = $("input[name=copyAll]:checked").val(),
		j = $("input[name=saveOldPagePath]:checked").val();
	if (params = {
		origCID: b,
		destCID: d,
		ctask: e,
		ccm_token: CCM_SECURITY_TOKEN,
		copyAll: i,
		saveOldPagePath: j
	}, 1 == i) {
		var k = ccmi18n_sitemap.copyProgressTitle;
		ccm_triggerProgressiveOperation(CCM_TOOLS_PATH + "/dashboard/sitemap_copy_all", [{
			name: "origCID",
			value: b
		}, {
			name: "destCID",
			value: d
		}], k, function() {
			$(".ui-dialog-content").dialog("close"), openSub(f, c, g, h, function() {
				openSub(f, d, g, h)
			})
		})
	} else jQuery.fn.dialog.showLoader(), $.getJSON(CCM_TOOLS_PATH + "/dashboard/sitemap_drag_request.php", params, function(i) {
		ccm_parseJSON(i, function() {
			if (jQuery.fn.dialog.closeAll(), jQuery.fn.dialog.hideLoader(), ccmAlert.hud(i.message, 2e3), 1 == a) {
				if ("undefined" == typeof CCM_LAUNCHER_SITEMAP) return setTimeout(function() {
					window.location.href = CCM_DISPATCHER_FILENAME + "?cID=" + i.cID
				}, 2e3), !1;
				if ("explore" == CCM_LAUNCHER_SITEMAP) return window.location.href = CCM_DISPATCHER_FILENAME + "/dashboard/sitemap/explore/-/" + d, !1;
				"search" == CCM_LAUNCHER_SITEMAP && (ccm_deactivateSearchResults(CCM_SEARCH_INSTANCE_ID), $("#ccm-" + CCM_SEARCH_INSTANCE_ID + "-advanced-search").ajaxSubmit(function(a) {
					ccm_parseAdvancedSearchResponse(a, CCM_SEARCH_INSTANCE_ID)
				}))
			}
			switch (e) {
			case "COPY":
			case "ALIAS":
				showBranch(b);
				break;
			case "MOVE":
				deleteBranchDirect(b)
			}
			openSub(f, c, g, h, function() {
				openSub(f, d, g, h)
			}), jQuery.fn.dialog.closeTop(), jQuery.fn.dialog.closeTop()
		})
	})
}, toggleSub = function(a, b, c, d) {
	ccm_hideMenus();
	var e = $("ul[tree-root-node-id=" + b + "][sitemap-instance-id=" + a + "]");
	"closed" == e.attr("tree-root-state") ? openSub(a, b, c, d) : closeSub(a, b, c, d)
}, ccm_sitemapDeleteForever = function(a, b, c) {
	var d = c ? ccmi18n_sitemap.emptyTrash : ccmi18n_sitemap.deletePages;
	ccm_triggerProgressiveOperation(CCM_TOOLS_PATH + "/dashboard/sitemap_delete_forever", [{
		name: "cID",
		value: b
	}], d, function() {
		if (c) {
			closeSub(a, b, "full", "");
			var d = $("ul[tree-root-node-id=" + b + "]").parent();
			d.find("img.tree-plus").remove(), d.find("span.ccm-sitemap-num-subpages").remove()
		} else deleteBranchFade(b), ccmAlert.hud(ccmi18n_sitemap.deletePageSuccessMsg, 2e3)
	})
}, setLoading = function(a) {
	var b = $("#tree-node" + a);
	b.removeClass("tree-node-" + b.attr("tree-node-type")), b.addClass("tree-node-loading")
}, removeLoading = function(a) {
	var b = $("#tree-node" + a);
	b.removeClass("tree-node-loading"), b.addClass("tree-node-" + b.attr("tree-node-type"))
}, openSub = function(a, b, c, d, e) {
	setLoading(b);
	var f = $("#tree-root" + b);
	cancelReorder(), ccm_sitemap_html = "", $.get(CCM_TOOLS_PATH + "/dashboard/sitemap_data.php?instance_id=" + a + "&node=" + b + "&display_mode=" + c + "&select_mode=" + d + "&selectedPageID=" + f.attr("selected-page-id"), function(c) {
		parseSitemapResponse(a, "full", d, b, c), activateLabels(a, "full", d), "move_copy_delete" != d && "select_page" != d && activateReorder(), setTimeout(function() {
			removeLoading(b), null != e && e()
		}, 200)
	})
}, closeSub = function(a, b, c, d) {
	var e = $("ul[tree-root-node-id=" + b + "][sitemap-instance-id=" + a + "]");
	tr_doAnim ? (setLoading(b), e.slideUp(150, "easeOutExpo", function() {
		removeLoading(b), e.attr("tree-root-state", "closed"), e.html(""), $("#ccm-tree-search" + b).hide(), $("#tree-collapse" + b).attr("src", CCM_IMAGE_PATH + "/dashboard/plus.jpg"), e.removeClass("ccm-sitemap-search-results")
	})) : (e.hide(), e.attr("tree-root-state", "closed"), e.removeClass("ccm-sitemap-search-results"), $("#ccm-tree-search" + b).hide(), $("#tree-collapse" + b).attr("src", CCM_IMAGE_PATH + "/dashboard/plus.jpg")), 1 == tr_moveCopyMode && $("#ccm-tree-search-trigger" + cID).show(), $.get(CCM_TOOLS_PATH + "/dashboard/sitemap_data.php?instance_id=" + a + "&select_mode=" + d + "&display_mode=" + c + "&node=" + b + "&display_mode=full&ctask=close-node")
}, toggleMove = function() {
	$("#copyThisPage").get(0) && ($("#copyThisPage").get(0).disabled = !0, $("#copyChildren").get(0).disabled = !0, $("#saveOldPagePath").attr("disabled", !1))
}, toggleAlias = function() {
	$("#copyThisPage").get(0) && ($("#copyThisPage").get(0).disabled = !0, $("#copyChildren").get(0).disabled = !0, $("#saveOldPagePath").attr("checked", !1), $("#saveOldPagePath").attr("disabled", "disabled"))
}, toggleCopy = function() {
	$("#copyThisPage").get(0) && ($("#copyThisPage").get(0).disabled = !1, $("#copyThisPage").get(0).checked = !0, $("#copyChildren").get(0).disabled = !1, $("#saveOldPagePath").attr("checked", !1), $("#saveOldPagePath").attr("disabled", "disabled"))
}, showSitemapMessage = function(a) {
	$("#ccm-sitemap-message").addClass("message"), $("#ccm-sitemap-message").html(a), $("#ccm-sitemap-message").fadeIn(200)
}, hideSitemapMessage = function() {
	$("#ccm-sitemap-message").hide()
}, ccmSitemapExploreNode = function(a, b, c, d, e) {
	jQuery.fn.dialog.showLoader(), $.get(CCM_TOOLS_PATH + "/dashboard/sitemap_data.php", {
		instance_id: a,
		display_mode: b,
		select_mode: c,
		node: d,
		selectedPageID: e
	}, function(b) {
		parseSitemapResponse(a, "explore", c, 0, b), activateLabels(a, "explore", c), jQuery.fn.dialog.hideLoader(), ccm_sitemap_html = ""
	})
}, ccmSitemapLoad = function(a, b, c, d, e, f) {
	"move_copy_delete" == c || "select_page" == c ? ccmSitemapExploreNode(a, b, c, d, e) : "full" == b ? (activateLabels(a, b, c), "move_copy_delete" != c && "select_page" != c && activateReorder(), tr_doAnim = !0, tr_parseSubnodes = !1, ccm_sitemap_html = "") : ("move_copy_delete" != c && "select_page" != c && $("ul[sitemap-instance-id=" + a + "]").sortable({
		cursor: "move",
		items: "li[draggable=true]",
		opacity: .5,
		stop: function() {
			var b = $("ul[sitemap-instance-id=" + a + "]").sortable("toArray"),
				c = "";
			for (i = 0; i < b.length; i++)"" != b[i] && (c += "&cID[]=" + b[i].substring(9));
			$.getJSON(CCM_TOOLS_PATH + "/dashboard/sitemap_update.php", c, function(a) {
				ccm_parseJSON(a, function() {})
			})
		}
	}), activateLabels(a, b, c)), f && f()
}, ccm_sitemapSetupSearch = function(a) {
	ccm_setupAdvancedSearch(a), ccm_sitemapSetupSearchPages(a), ccm_searchActivatePostFunction[a] = function() {
		ccm_sitemapSetupSearchPages(a), ccm_sitemapSearchSetupCheckboxes(a)
	}, ccm_sitemapSearchSetupCheckboxes(a)
}, ccm_sitemapSearchSetupCheckboxes = function(a) {
	$("#ccm-" + a + "-list-cb-all").click(function(b) {
		b.stopPropagation(), 1 == $(this).prop("checked") ? ($(".ccm-list-record td.ccm-" + a + "-list-cb input[type=checkbox]").attr("checked", !0), $("#ccm-" + a + "-list-multiple-operations").attr("disabled", !1)) : ($(".ccm-list-record td.ccm-" + a + "-list-cb input[type=checkbox]").attr("checked", !1), $("#ccm-" + a + "-list-multiple-operations").attr("disabled", !0))
	}), $("td.ccm-" + a + "-list-cb input[type=checkbox]").click(function(b) {
		b.stopPropagation(), $("td.ccm-" + a + "-list-cb input[type=checkbox]:checked").length > 0 ? $("#ccm-" + a + "-list-multiple-operations").attr("disabled", !1) : $("#ccm-" + a + "-list-multiple-operations").attr("disabled", !0)
	}), $("#ccm-" + a + "-list-multiple-operations").change(function() {
		var b = $(this).val();
		switch (cIDstring = "", $("td.ccm-" + a + "-list-cb input[type=checkbox]:checked").each(function() {
			cIDstring = cIDstring + "&cID[]=" + $(this).val()
		}), b) {
		case "delete":
			jQuery.fn.dialog.open({
				width: 500,
				height: 400,
				modal: !1,
				appendButtons: !0,
				href: CCM_TOOLS_PATH + "/pages/delete?" + cIDstring + "&searchInstance=" + a,
				title: ccmi18n_sitemap.deletePages
			});
			break;
		case "design":
			jQuery.fn.dialog.open({
				width: 610,
				height: 405,
				modal: !1,
				appendButtons: !0,
				href: CCM_TOOLS_PATH + "/pages/design?" + cIDstring + "&searchInstance=" + a,
				title: ccmi18n_sitemap.pageDesign
			});
			break;
		case "move_copy":
			jQuery.fn.dialog.open({
				width: 640,
				height: 340,
				modal: !1,
				href: CCM_TOOLS_PATH + "/sitemap_overlay?instance_id=" + a + "&select_mode=move_copy_delete&" + cIDstring,
				title: ccmi18n_sitemap.moveCopyPage
			});
			break;
		case "speed_settings":
			jQuery.fn.dialog.open({
				width: 610,
				height: 340,
				modal: !1,
				appendButtons: !0,
				href: CCM_TOOLS_PATH + "/pages/speed_settings?" + cIDstring,
				title: ccmi18n_sitemap.speedSettingsTitle
			});
			break;
		case "permissions":
			jQuery.fn.dialog.open({
				width: 430,
				height: 630,
				modal: !1,
				appendButtons: !0,
				href: CCM_TOOLS_PATH + "/pages/permissions?" + cIDstring,
				title: ccmi18n_sitemap.pagePermissionsTitle
			});
			break;
		case "permissions_add_access":
			jQuery.fn.dialog.open({
				width: 440,
				height: 200,
				modal: !1,
				appendButtons: !0,
				href: CCM_TOOLS_PATH + "/pages/permissions_access?task=add&" + cIDstring,
				title: ccmi18n_sitemap.pagePermissionsTitle
			});
			break;
		case "permissions_remove_access":
			jQuery.fn.dialog.open({
				width: 440,
				height: 300,
				modal: !1,
				appendButtons: !0,
				href: CCM_TOOLS_PATH + "/pages/permissions_access?task=remove&" + cIDstring,
				title: ccmi18n_sitemap.pagePermissionsTitle
			});
			break;
		case "properties":
			jQuery.fn.dialog.open({
				width: 630,
				height: 450,
				modal: !1,
				href: CCM_TOOLS_PATH + "/pages/bulk_metadata_update?" + cIDstring,
				title: ccmi18n_sitemap.pagePropertiesTitle
			})
		}
		$(this).get(0).selectedIndex = 0
	})
}, ccm_sitemapSetupSearchPages = function(instance_id) {
	$("#ccm-" + instance_id + "-list tr").click(function(e) {
		var node = $(this);
		if (node.hasClass("ccm-results-list-header")) return !1;
		if ("select_page" == node.attr("sitemap-select-mode")) {
			var callback = node.attr("sitemap-select-callback");
			(null == callback || "" == callback || "undefined" == typeof callback) && (callback = "ccm_selectSitemapNode"), eval(callback + "(node.attr('cID'), unescape(node.attr('cName')));"), jQuery.fn.dialog.closeTop()
		} else if ("move_copy_delete" == node.attr("sitemap-select-mode")) {
			var destCID = node.attr("cID"),
				origCID = node.attr("selected-page-id");
			selectMoveCopyTarget(node.attr("sitemap-instance-id"), node.attr("sitemap-display-mode"), node.attr("sitemap-select-mode"), destCID, origCID)
		} else params = {
			cID: node.attr("cID"),
			select_mode: node.attr("sitemap-select-mode"),
			display_mode: node.attr("sitemap-display-mode"),
			instance_id: node.attr("sitemap-instance-id"),
			isTrash: node.attr("tree-node-istrash"),
			inTrash: node.attr("tree-node-intrash"),
			canCompose: node.attr("tree-node-cancompose"),
			canEditProperties: node.attr("tree-node-can-edit-properties"),
			canEditSpeedSettings: node.attr("tree-node-can-edit-speed-settings"),
			canEditPermissions: node.attr("tree-node-can-edit-permissions"),
			canEditDesign: node.attr("tree-node-can-edit-design"),
			canViewVersions: node.attr("tree-node-can-view-versions"),
			canDelete: node.attr("tree-node-can-delete"),
			canAddSubpages: node.attr("tree-node-can-add-subpages"),
			canAddExternalLinks: node.attr("tree-node-can-add-external-links"),
			cNumChildren: node.attr("cNumChildren"),
			cAlias: node.attr("cAlias")
		}, showPageMenu(params, e)
	})
}, ccm_sitemapSelectDisplayMode = function(a, b, c, d) {
	var e = $("ul[sitemap-instance-id=" + a + "]");
	if (e.html(""), e.attr("sitemap-display-mode", b), e.attr("sitemap-select-mode", c), e.attr("sitemap-display-mode", b), "explore" == b) var f = 1;
	else var f = 0;
	ccmSitemapLoad(a, b, c, f, d, function() {
		"explore" == b ? $("div[sitemap-wrapper=1][sitemap-instance-id=" + a + "]").addClass("ccm-sitemap-explore") : $("div[sitemap-wrapper=1][sitemap-instance-id=" + a + "]").removeClass("ccm-sitemap-explore")
	}), $.get(CCM_TOOLS_PATH + "/dashboard/sitemap_data.php?task=save_sitemap_display_mode&display_mode=" + b)
}, ccm_sitemapDeletePages = function(a) {
	var b = $("#ccm-" + a + "-delete-form").formToArray(!0);
	ccm_triggerProgressiveOperation(CCM_TOOLS_PATH + "/pages/delete", b, ccmi18n_sitemap.deletePages, function() {
		if ($(".ui-dialog-content").dialog("close"), ccm_deactivateSearchResults(a), $("#ccm-" + a + "-advanced-search").ajaxSubmit(function(b) {
			ccm_parseAdvancedSearchResponse(b, a)
		}), isTrash) {
			closeSub(instance_id, nodeID, "full", "");
			var b = $("ul[tree-root-node-id=" + nodeID + "]").parent();
			b.find("img.tree-plus").remove(), b.find("span.ccm-sitemap-num-subpages").remove()
		} else deleteBranchFade(nodeID), ccmAlert.hud(ccmi18n_sitemap.deletePageSuccessMsg, 2e3)
	})
}, ccm_sitemapUpdateDesign = function(a) {
	$("#ccm-" + a + "-design-form").ajaxSubmit(function(b) {
		ccm_parseJSON(b, function() {
			jQuery.fn.dialog.closeTop(), ccm_deactivateSearchResults(a), $("#ccm-" + a + "-advanced-search").ajaxSubmit(function(b) {
				ccm_parseAdvancedSearchResponse(b, a)
			})
		})
	})
}, $(function() {
	$(document).click(function() {
		ccm_hideMenus(), $("div.tree-label").removeClass("tree-label-selected")
	}), $("#ccm-show-all-pages-cb").click(function() {
		var a = 1 == $(this).get(0).checked ? 1 : 0;
		$.get(CCM_TOOLS_PATH + "/dashboard/sitemap_data.php?show_system=" + a, function() {
			location.reload()
		})
	})
}), ccm_statusBar = {
	items: [],
	addItem: function(a) {
		this.items.push(a)
	},
	activate: function(a) {
		if (a || (a = "ccm-page-controls-wrapper"), this.items.length > 0) {
			var b = '<div id="ccm-page-status-bar" class="ccm-ui">';
			for (i = 0; i < this.items.length; i++) {
				var c = this.items[i],
					d = "",
					e = c.getButtons();
				for (j = 0; j < e.length; j++) {
					attribs = "";
					var f = "",
						g = "";
					"" != e[j].getInnerButtonLeftHTML() && (f = e[j].getInnerButtonLeftHTML() + " "), "" != e[j].getInnerButtonRightHTML() && (g = " " + e[j].getInnerButtonRightHTML());
					var h = e[j].getAttributes();
					for (k in h) attribs += h[k].key + '="' + h[k].value + '" ';
					d += "" != e[j].getURL() ? '<a href="' + e[j].getURL() + '" ' + attribs + ' class="btn btn-small ' + e[j].getCSSClass() + '">' + f + e[j].getLabel() + g + "</a>" : '<button type="submit" ' + attribs + ' name="action_' + e[j].getAction() + '" class="btn-small btn ' + e[j].getCSSClass() + '">' + f + e[j].getLabel() + g + "</button>"
				}
				var l = '<form method="post" action="' + c.getAction() + '" id="ccm-status-bar-form-' + i + '" ' + (c.useAjaxForm ? 'class="ccm-status-bar-ajax-form"' : "") + '><div class="alert-message alert ' + c.getCSSClass() + '"><button type="button" class="close" data-dismiss="alert">×</button><span>' + c.getDescription() + '</span> <div class="ccm-page-status-bar-buttons">' + d + "</div></div></form>";
				b += l
			}
			b += "</div>", $("#" + a).append(b), $("#ccm-page-status-bar .dialog-launch").dialog(), $("#ccm-page-status-bar .alert").bind("closed", function() {
				$(this).remove();
				var a = $("#ccm-page-status-bar .alert:visible").length;
				0 == a && $("#ccm-page-status-bar").remove()
			}), $("#ccm-page-status-bar .ccm-status-bar-ajax-form").ajaxForm({
				dataType: "json",
				beforeSubmit: function() {
					jQuery.fn.dialog.showLoader()
				},
				success: function(a) {
					a.redirect && (window.location.href = a.redirect)
				}
			})
		}
	}
}, ccm_statusBarItem = function() {
	this.css = "", this.description = "", this.buttons = [], this.action = "", this.useAjaxForm = !1, this.setCSSClass = function(a) {
		this.css = a
	}, this.enableAjaxForm = function() {
		this.useAjaxForm = !0
	}, this.setDescription = function(a) {
		this.description = a
	}, this.getCSSClass = function() {
		return this.css
	}, this.getDescription = function() {
		return this.description
	}, this.addButton = function(a) {
		this.buttons.push(a)
	}, this.getButtons = function() {
		return this.buttons
	}, this.setAction = function(a) {
		this.action = a
	}, this.getAction = function() {
		return this.action
	}
}, ccm_statusBarItemButton = function() {
	this.css = "", this.innerbuttonleft = "", this.innerbuttonright = "", this.label = "", this.action = "", this.url = "", this.attribs = new Array, this.setLabel = function(a) {
		this.label = a
	}, this.setCSSClass = function(a) {
		this.css = a
	}, this.setInnerButtonLeftHTML = function(a) {
		this.innerbuttonleft = a
	}, this.setInnerButtonRightHTML = function(a) {
		this.innerbuttonright = a
	}, this.setAction = function(a) {
		this.action = a
	}, this.getAttributes = function() {
		return this.attribs
	}, this.addAttribute = function(a, b) {
		this.attribs.push({
			key: a,
			value: b
		})
	}, this.getAction = function() {
		return this.action
	}, this.setURL = function(a) {
		this.url = a
	}, this.getURL = function() {
		return this.url
	}, this.getCSSClass = function() {
		return this.css
	}, this.getInnerButtonLeftHTML = function() {
		return this.innerbuttonleft
	}, this.getInnerButtonRightHTML = function() {
		return this.innerbuttonright
	}, this.getLabel = function() {
		return this.label
	}
}, ccm_activateTabBar = function(a) {
	$("#ccm-tab-content-" + a.find("li[class=active] a").attr("data-tab")).show(), a.find("a").unbind().click(function() {
		a.find("li").removeClass("active"), $(this).parent().addClass("active"), a.find("a").each(function() {
			$("#ccm-tab-content-" + $(this).attr("data-tab")).hide()
		});
		var b = $(this).attr("data-tab");
		return $("#ccm-tab-content-" + b).show(), !1
	})
};
var ccm_editorCurrentAuxTool = !1;
ccm_editorSetupImagePicker = function() {
	tinyMCE.activeEditor.focus();
	var a = tinyMCE.activeEditor.selection.getBookmark();
	return ccm_chooseAsset = function(b) {
		var c = tinyMCE.activeEditor;
		c.selection.moveToBookmark(a);
		var d = {};
		tinymce.extend(d, {
			src: b.filePathInline,
			alt: b.title,
			width: b.width,
			height: b.height
		}), c.execCommand("mceInsertContent", !1, '<img id="__mce_tmp" src="javascript:;" />', {
			skip_undo: 1
		}), c.dom.setAttribs("__mce_tmp", d), c.dom.setAttrib("__mce_tmp", "id", ""), c.undoManager.add()
	}, !1
}, ccm_editorSetupFilePicker = function() {
	tinyMCE.activeEditor.focus();
	var a = tinyMCE.activeEditor.selection.getBookmark();
	return ccm_chooseAsset = function(b) {
		var c = tinyMCE.activeEditor;
		c.selection.moveToBookmark(a);
		var d = c.selection.getContent();
		if ("" != d) c.execCommand("mceInsertLink", !1, {
			href: b.filePath,
			title: b.title,
			target: null,
			"class": null
		});
		else {
			var e = '<a href="' + b.filePath + '">' + b.title + "</a>";
			tinyMCE.execCommand("mceInsertRawHTML", !1, e, !0)
		}
	}, !1
}, ccm_editorSitemapOverlay = function() {
	tinyMCE.activeEditor.focus();
	var a = tinyMCE.activeEditor.selection.getBookmark();
	$.fn.dialog.open({
		title: ccmi18n_sitemap.choosePage,
		href: CCM_TOOLS_PATH + "/sitemap_search_selector.php?sitemap_select_mode=select_page&callback=ccm_editorSelectSitemapNode",
		width: "90%",
		modal: !1,
		height: "70%"
	}), ccm_editorSelectSitemapNode = function(b, c) {
		var d = tinyMCE.activeEditor;
		d.selection.moveToBookmark(a);
		var e = d.selection.getContent(),
			f = CCM_BASE_URL + CCM_DISPATCHER_FILENAME + "?cID=" + b;
		if ("" != e) d.execCommand("mceInsertLink", !1, {
			href: f,
			title: c,
			target: null,
			"class": null
		});
		else {
			var e = '<a href="' + CCM_BASE_URL + CCM_DISPATCHER_FILENAME + "?cID=" + b + '" title="' + c + '">' + c + "</a>";
			tinyMCE.execCommand("mceInsertRawHTML", !1, e, !0)
		}
	}
};
var ccm_arrangeMode = !1,
	ccm_selectedDomID = !1,
	ccm_isBlockError = !1,
	ccm_activeMenu = !1,
	ccm_blockError = !1;
ccm_menuInit = function(a) {
	if (CCM_EDIT_MODE && !CCM_ARRANGE_MODE) switch (a.type) {
	case "BLOCK":
		$("#b" + a.bID + "-" + a.aID).mouseover(function() {
			ccm_activate(a, "#b" + a.bID + "-" + a.aID)
		});
		break;
	case "AREA":
		$("#a" + a.aID + "controls").mouseover(function() {
			ccm_activate(a, "#a" + a.aID + "controls")
		})
	}
}, ccm_showBlockMenu = function(a, b) {
	ccm_hideMenus(), b.stopPropagation(), ccm_activeMenu = !0;
	var c = document.getElementById("ccm-block-menu" + a.bID + "-" + a.aID);
	if (c) c = $("#ccm-block-menu" + a.bID + "-" + a.aID);
	else {
		el = document.createElement("DIV"), el.id = "ccm-block-menu" + a.bID + "-" + a.aID, el.className = "ccm-menu ccm-ui", el.style.display = "block", el.style.visibility = "hidden", document.body.appendChild(el), c = $("#ccm-block-menu" + a.bID + "-" + a.aID), c.css("position", "absolute");
		var d = '<div class="popover"><div class="arrow"></div><div class="inner"><div class="content">';
		d += "<ul>", a.canWrite && a.hasEditDialog && (d += a.editInline ? '<li><a class="ccm-menu-icon ccm-icon-edit-menu" onclick="ccm_hideMenus()" id="menuEdit' + a.bID + "-" + a.aID + '" href="' + CCM_DISPATCHER_FILENAME + "?cID=" + a.cID + "&bID=" + a.bID + "&arHandle=" + encodeURIComponent(a.arHandle) + "&btask=edit#_edit" + a.bID + '">' + ccmi18n.editBlock + "</a></li>" : '<li><a class="ccm-menu-icon ccm-icon-edit-menu" onclick="ccm_hideMenus()" dialog-title="' + ccmi18n.editBlockWithName.replace(/%s/g, a.btName) + '" dialog-append-buttons="true" dialog-modal="false" dialog-on-close="ccm_blockWindowAfterClose()" dialog-width="' + a.width + '" dialog-height="' + a.height + '" id="menuEdit' + a.bID + "-" + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_block_popup.php?cID=" + a.cID + "&bID=" + a.bID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&btask=edit">' + ccmi18n.editBlock + "</a></li>"), a.canWriteStack && (d += '<li><a class="ccm-menu-icon ccm-icon-edit-menu" id="menuEdit' + a.bID + "-" + a.aID + '" href="' + CCM_DISPATCHER_FILENAME + "/dashboard/blocks/stacks/-/view_details/" + a.stID + '">' + ccmi18n.editStackContents + "</a></li>", d += '<li class="header"></li>'), a.canCopyToScrapbook && (d += '<li><a class="ccm-menu-icon ccm-icon-clipboard-menu" id="menuAddToScrapbook' + a.bID + "-" + a.aID + '" href="#" onclick="javascript:ccm_addToScrapbook(' + a.cID + "," + a.bID + ",'" + encodeURIComponent(a.arHandle) + "');return false;\">" + ccmi18n.copyBlockToScrapbook + "</a></li>"), a.canArrange && (d += '<li><a class="ccm-menu-icon ccm-icon-move-menu" id="menuArrange' + a.bID + "-" + a.aID + '" href="javascript:ccm_arrangeInit()">' + ccmi18n.arrangeBlock + "</a></li>"), a.canDelete && (d += '<li><a class="ccm-menu-icon ccm-icon-delete-menu" id="menuDelete' + a.bID + "-" + a.aID + '" href="#" onclick="javascript:ccm_deleteBlock(' + a.cID + "," + a.bID + "," + a.aID + ", '" + encodeURIComponent(a.arHandle) + "', '" + a.deleteMessage + "');return false;\">" + ccmi18n.deleteBlock + "</a></li>"), (a.canDesign || a.canEditBlockCustomTemplate) && (d += '<li class="ccm-menu-separator"></li>'), a.canDesign && (d += '<li><a class="ccm-menu-icon ccm-icon-design-menu" onclick="ccm_hideMenus()" dialog-modal="false" dialog-title="' + ccmi18n.changeBlockBaseStyle + '" dialog-width="475" dialog-height="500" dialog-append-buttons="true" id="menuChangeCSS' + a.bID + "-" + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_block_popup.php?cID=" + a.cID + "&bID=" + a.bID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&btask=block_css&modal=true&width=300&height=100" title="' + ccmi18n.changeBlockCSS + '">' + ccmi18n.changeBlockCSS + "</a></li>"), a.canEditBlockCustomTemplate && (d += '<li><a class="ccm-menu-icon ccm-icon-custom-template-menu" onclick="ccm_hideMenus()" dialog-append-buttons="true" dialog-modal="false" dialog-title="' + ccmi18n.changeBlockTemplate + '" dialog-width="300" dialog-height="275" id="menuChangeTemplate' + a.bID + "-" + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_block_popup.php?cID=" + a.cID + "&bID=" + a.bID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&btask=template&modal=true&width=300&height=275" title="' + ccmi18n.changeBlockTemplate + '">' + ccmi18n.changeBlockTemplate + "</a></li>"), (a.canModifyGroups || a.canScheduleGuestAccess || a.canAliasBlockOut || a.canSetupComposer) && (d += '<li class="ccm-menu-separator"></li>'), a.canModifyGroups && (d += '<li><a title="' + ccmi18n.setBlockPermissions + '" onclick="ccm_hideMenus()" class="ccm-menu-icon ccm-icon-permissions-menu" dialog-width="420" dialog-height="350" id="menuBlockGroups' + a.bID + "-" + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_block_popup.php?cID=" + a.cID + "&bID=" + a.bID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&btask=groups" dialog-append-buttons="true" dialog-title="' + ccmi18n.setBlockPermissions + '">' + ccmi18n.setBlockPermissions + "</a></li>"), a.canScheduleGuestAccess && (d += '<li><a title="' + ccmi18n.scheduleGuestAccess + '" onclick="ccm_hideMenus()" class="ccm-menu-icon ccm-icon-clock-menu" dialog-width="500" dialog-height="220" id="menuBlockViewClock' + a.bID + "-" + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_block_popup.php?cID=" + a.cID + "&bID=" + a.bID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&btask=guest_timed_access" dialog-append-buttons="true" dialog-title="' + ccmi18n.scheduleGuestAccess + '">' + ccmi18n.scheduleGuestAccess + "</a></li>"), a.canAliasBlockOut && (d += '<li><a class="ccm-menu-icon ccm-icon-setup-child-pages-menu" dialog-append-buttons="true" onclick="ccm_hideMenus()" dialog-width="550" dialog-height="450" id="menuBlockAliasOut' + a.bID + "-" + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_block_popup.php?cID=" + a.cID + "&bID=" + a.bID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&btask=child_pages" dialog-title="' + ccmi18n.setBlockAlias + '">' + ccmi18n.setBlockAlias + "</a></li>"), a.canSetupComposer && (d += '<li><a class="ccm-menu-icon ccm-icon-setup-composer-menu" dialog-append-buttons="true" onclick="ccm_hideMenus()" dialog-width="450" dialog-modal="false" dialog-height="130" id="menuBlockSetupComposer' + a.bID + "-" + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_block_popup.php?cID=" + a.cID + "&bID=" + a.bID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&btask=composer" dialog-title="' + ccmi18n.setBlockComposerSettings + '">' + ccmi18n.setBlockComposerSettings + "</a></li>"), d += "</ul>", d += "</div></div></div>", c.append(d), a.canWrite && !a.editInline && $("a#menuEdit" + a.bID + "-" + a.aID).dialog(), a.canEditBlockCustomTemplate && $("a#menuChangeTemplate" + a.bID + "-" + a.aID).dialog(), a.canDesign && $("a#menuChangeCSS" + a.bID + "-" + a.aID).dialog(), a.canAliasBlockOut && $("a#menuBlockAliasOut" + a.bID + "-" + a.aID).dialog(), a.canSetupComposer && $("a#menuBlockSetupComposer" + a.bID + "-" + a.aID).dialog(), a.canModifyGroups && $("#menuBlockGroups" + a.bID + "-" + a.aID).dialog(), a.canScheduleGuestAccess && $("#menuBlockViewClock" + a.bID + "-" + a.aID).dialog()
	}
	ccm_fadeInMenu(c, b)
}, ccm_reloadAreaMenuPermissions = function(a, b) {
	var c = window["ccm_areaMenuObj" + a];
	if (c) {
		var d = CCM_TOOLS_PATH + "/reload_area_permissions_js.php?arHandle=" + c.arHandle + "&cID=" + b + "&maximumBlocks=" + c.maximumBlocks;
		$.getScript(d)
	}
}, ccm_openAreaAddBlock = function(a, b, c) {
	b || (b = 0), c || (c = CCM_CID), $.fn.dialog.open({
		title: ccmi18n.blockAreaMenu,
		href: CCM_TOOLS_PATH + "/edit_area_popup.php?cID=" + c + "&atask=add&arHandle=" + a + "&addOnly=" + b,
		width: 550,
		modal: !1,
		height: 380
	})
}, ccm_showAreaMenu = function(a, b) {
	var c = a.addOnly ? 1 : 0;
	if (ccm_activeMenu = !0, b.shiftKey) ccm_openAreaAddBlock(a.arHandle, c);
	else {
		b.stopPropagation();
		var d = document.getElementById("ccm-area-menu" + a.aID);
		if (d) d = $("#ccm-area-menu" + a.aID);
		else {
			el = document.createElement("DIV"), el.id = "ccm-area-menu" + a.aID, el.className = "ccm-menu ccm-ui", el.style.display = "block", el.style.visibility = "hidden", document.body.appendChild(el), d = $("#ccm-area-menu" + a.aID), d.css("position", "absolute");
			var e = '<div class="popover"><div class="arrow"></div><div class="inner"><div class="content">';
			e += "<ul>", a.canAddBlocks && (e += '<li><a onclick="ccm_hideMenus()" class="ccm-menu-icon ccm-icon-add-block-menu" dialog-title="' + ccmi18n.addBlockNew + '" dialog-modal="false" dialog-width="550" dialog-height="380" id="menuAddNewBlock' + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_area_popup.php?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(a.arHandle) + "&atask=add&addOnly=" + c + '">' + ccmi18n.addBlockNew + "</a></li>", e += '<li><a onclick="ccm_hideMenus()" class="ccm-menu-icon ccm-icon-clipboard-menu" dialog-title="' + ccmi18n.addBlockPaste + '" dialog-modal="false" dialog-width="550" dialog-height="380" id="menuAddPaste' + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_area_popup.php?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(a.arHandle) + "&atask=paste&addOnly=" + c + '">' + ccmi18n.addBlockPaste + "</a></li>"), a.canAddStacks && (e += '<li><a onclick="ccm_hideMenus()" class="ccm-menu-icon ccm-icon-add-stack-menu" dialog-title="' + ccmi18n.addBlockStack + '" dialog-modal="false" dialog-width="550" dialog-height="380" id="menuAddNewStack' + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_area_popup.php?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(a.arHandle) + "&atask=add_from_stack&addOnly=" + c + '">' + ccmi18n.addBlockStack + "</a></li>"), a.canAddBlocks && (a.canDesign || a.canLayout) && (e += '<li class="ccm-menu-separator"></li>'), a.canLayout && (e += '<li><a onclick="ccm_hideMenus()" class="ccm-menu-icon ccm-icon-add-layout-menu" dialog-title="' + ccmi18n.addAreaLayout + '" dialog-modal="false" dialog-width="400" dialog-height="300" dialog-append-buttons="true" id="menuAreaLayout' + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_area_popup.php?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&atask=layout">' + ccmi18n.addAreaLayout + "</a></li>"), a.canDesign && (e += '<li><a onclick="ccm_hideMenus()" class="ccm-menu-icon ccm-icon-design-menu" dialog-title="' + ccmi18n.changeAreaCSS + '" dialog-modal="false" dialog-append-buttons="true" dialog-width="475" dialog-height="500" id="menuAreaStyle' + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_area_popup.php?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&atask=design">' + ccmi18n.changeAreaCSS + "</a></li>"), a.canWrite && a.canModifyGroups && (e += '<li class="ccm-menu-separator"></li>'), a.canModifyGroups && (e += '<li><a onclick="ccm_hideMenus()" title="' + ccmi18n.setAreaPermissions + '" dialog-append-buttons="true" dialog-modal="false" class="ccm-menu-icon ccm-icon-permissions-menu" dialog-width="420" dialog-height="425" id="menuAreaGroups' + a.aID + '" href="' + CCM_TOOLS_PATH + "/edit_area_popup.php?cID=" + CCM_CID + "&arHandle=" + encodeURIComponent(a.arHandle) + '&atask=groups" dialog-title="' + ccmi18n.setAreaPermissions + '">' + ccmi18n.setAreaPermissions + "</a></li>"), e += "</ul>", e += "</div></div></div>", d.append(e), a.canAddBlocks && ($("a#menuAddNewBlock" + a.aID).dialog(), $("a#menuAddPaste" + a.aID).dialog()), a.canAddStacks && $("a#menuAddNewStack" + a.aID).dialog(), a.canLayout && $("a#menuAreaLayout" + a.aID).dialog(), a.canDesign && $("a#menuAreaStyle" + a.aID).dialog(), a.canModifyGroups && $("a#menuAreaGroups" + a.aID).dialog()
		}
		ccm_fadeInMenu(d, b)
	}
}, ccm_hideHighlighter = function() {
	$("#ccm-highlighter").css("display", "none"), $("div.ccm-menu-hotspot-active").removeClass("ccm-menu-hotspot-active")
}, ccm_addError = function(a) {
	ccm_isBlockError || (ccm_blockError = "", ccm_blockError += "<ul>"), ccm_isBlockError = !0, ccm_blockError += "<li>" + a + "</li>"
}, ccm_resetBlockErrors = function() {
	ccm_isBlockError = !1, ccm_blockError = ""
}, ccm_addToScrapbook = function(a, b, c) {
	ccm_mainNavDisableDirectExit(), ccm_hideHighlighter(), $.ajax({
		type: "POST",
		url: CCM_TOOLS_PATH + "/pile_manager.php",
		data: "cID=" + a + "&bID=" + b + "&arHandle=" + c + "&btask=add&scrapbookName=userScrapbook",
		success: function() {
			ccm_hideHighlighter(), ccmAlert.hud(ccmi18n.copyBlockToScrapbookMsg, 2e3, "add", ccmi18n.copyBlockToScrapbook)
		}
	})
}, ccm_deleteBlock = function(a, b, c, d, e) {
	confirm(e) && (ccm_mainNavDisableDirectExit(), ccm_hideHighlighter(), $d = $("#b" + b + "-" + c), $d.hide(), ccmAlert.hud(ccmi18n.deleteBlockMsg, 2e3, "delete_small", ccmi18n.deleteBlock), $.ajax({
		type: "POST",
		url: CCM_DISPATCHER_FILENAME,
		data: "cID=" + a + "&ccm_token=" + CCM_SECURITY_TOKEN + "&isAjax=true&btask=remove&bID=" + b + "&arHandle=" + d
	}), ccm_reloadAreaMenuPermissions(c, a), "function" == typeof window.ccm_parseBlockResponsePost && ccm_parseBlockResponsePost({}))
}, ccm_hideMenus = function() {
	ccm_activeMenu = !1, $("div.ccm-menu").hide(), $("div.ccm-menu").css("visibility", "hidden"), $("div.ccm-menu").show()
}, ccm_parseBlockResponse = function(r, currentBlockID, task) {
	try {
		if (r = r.replace(/(<([^>]+)>)/gi, ""), resp = eval("(" + r + ")"), 1 == resp.error) {
			var message = "<ul>";
			for (i = 0; i < resp.response.length; i++) message += "<li>" + resp.response[i] + "</li>";
			message += "</ul>", ccmAlert.notice(ccmi18n.error, message)
		} else {
			ccm_blockWindowClose(), cID = resp.cID ? resp.cID : CCM_CID;
			var action = CCM_TOOLS_PATH + "/edit_block_popup?cID=" + cID + "&bID=" + resp.bID + "&arHandle=" + encodeURIComponent(resp.arHandle) + "&btask=view_edit_mode";
			$.get(action, function(a) {
				"add" == task ? $("#a" + resp.aID + " div.ccm-area-styles-a" + resp.aID).length > 0 ? $("#a" + resp.aID + " div.ccm-area-styles-a" + resp.aID).append(a) : $("#a" + resp.aID).append(a) : $("#b" + currentBlockID + "-" + resp.aID).before(a).remove(), jQuery.fn.dialog.hideLoader(), ccm_mainNavDisableDirectExit(), "add" == task ? (ccmAlert.hud(ccmi18n.addBlockMsg, 2e3, "add", ccmi18n.addBlock), jQuery.fn.dialog.closeAll()) : ccmAlert.hud(ccmi18n.updateBlockMsg, 2e3, "success", ccmi18n.updateBlock), "function" == typeof window.ccm_parseBlockResponsePost && ccm_parseBlockResponsePost(resp)
			}), ccm_reloadAreaMenuPermissions(resp.aID, cID)
		}
	} catch (e) {
		ccmAlert.notice(ccmi18n.error, r)
	}
}, ccm_mainNavDisableDirectExit = function(a) {
	$("#ccm-exit-edit-mode-direct").hide(), a || $("#ccm-exit-edit-mode-comment").show()
}, ccm_setupBlockForm = function(a, b, c) {
	a.ajaxForm({
		type: "POST",
		iframe: !0,
		beforeSubmit: function() {
			return ccm_hideHighlighter(), $("input[name=ccm-block-form-method]").val("AJAX"), jQuery.fn.dialog.showLoader(), ccm_blockFormSubmit()
		},
		success: function(a) {
			ccm_parseBlockResponse(a, b, c)
		}
	})
}, ccm_activate = function(a, b) {
	return ccm_arrangeMode || ccm_activeMenu ? !1 : (ccm_selectedDomID && $(ccm_selectedDomID).removeClass("ccm-menu-hotspot-active"), aobj = $(b), aobj.addClass("ccm-menu-hotspot-active"), ccm_selectedDomID = b, offs = aobj.offset(), $("#ccm-highlighter").hide(), $("#ccm-highlighter").css("width", aobj.outerWidth()), $("#ccm-highlighter").css("height", aobj.outerHeight()), $("#ccm-highlighter").css("top", offs.top), $("#ccm-highlighter").css("left", offs.left), $("#ccm-highlighter").fadeIn(120, "easeOutExpo"), $("#ccm-highlighter").mouseout(function(a) {
		ccm_activeMenu || (a.target ? 0 == $(a.toElement).parents("div.ccm-menu").length && ccm_hideHighlighter() : ccm_hideHighlighter())
	}), $("#ccm-highlighter").unbind("click"), void $("#ccm-highlighter").click(function(b) {
		switch (a.type) {
		case "BLOCK":
			ccm_showBlockMenu(a, b);
			break;
		case "AREA":
			ccm_showAreaMenu(a, b)
		}
	}))
}, ccm_editInit = function() {
	document.write = function() {}, $(document.body).append('<div style="position: absolute; display:none" id="ccm-highlighter">&nbsp;</div>'), $(document).click(function() {
		ccm_hideMenus()
	}), $("div.ccm-menu a").bind("click.hide-menu", function() {
		return ccm_hideMenus(), !1
	})
}, ccm_triggerSelectUser = function(a, b, c) {
	alert(a), alert(b), alert(c)
}, ccm_setupUserSearch = function(a) {
	$(".chosen-select").chosen(ccmi18n_chosen), $("#ccm-user-list-cb-all").click(function() {
		1 == $(this).prop("checked") ? ($(".ccm-list-record td.ccm-user-list-cb input[type=checkbox]").attr("checked", !0), $("#ccm-user-list-multiple-operations").attr("disabled", !1)) : ($(".ccm-list-record td.ccm-user-list-cb input[type=checkbox]").attr("checked", !1), $("#ccm-user-list-multiple-operations").attr("disabled", !0))
	}), $("td.ccm-user-list-cb input[type=checkbox]").click(function() {
		$("td.ccm-user-list-cb input[type=checkbox]:checked").length > 0 ? $("#ccm-user-list-multiple-operations").attr("disabled", !1) : $("#ccm-user-list-multiple-operations").attr("disabled", !0)
	}), $("#ccm-user-list-multiple-operations").change(function() {
		var b = $(this).val();
		switch (b) {
		case "choose":
			;
			$("td.ccm-user-list-cb input[type=checkbox]:checked").each(function() {
				ccm_triggerSelectUser($(this).val(), $(this).attr("user-name"), $(this).attr("user-email"))
			}), jQuery.fn.dialog.closeTop();
			break;
		case "properties":
			uIDstring = "", $("td.ccm-user-list-cb input[type=checkbox]:checked").each(function() {
				uIDstring = uIDstring + "&uID[]=" + $(this).val()
			}), jQuery.fn.dialog.open({
				width: 630,
				height: 450,
				modal: !1,
				href: CCM_TOOLS_PATH + "/users/bulk_properties?" + uIDstring,
				title: ccmi18n.properties
			});
			break;
		case "activate":
			uIDstring = "", $("td.ccm-user-list-cb input[type=checkbox]:checked").each(function() {
				uIDstring = uIDstring + "&uID[]=" + $(this).val()
			}), jQuery.fn.dialog.open({
				width: 630,
				height: 450,
				modal: !1,
				href: CCM_TOOLS_PATH + "/users/bulk_activate?searchInstance=" + a + "&" + uIDstring,
				title: ccmi18n.user_activate
			});
			break;
		case "deactivate":
			uIDstring = "", $("td.ccm-user-list-cb input[type=checkbox]:checked").each(function() {
				uIDstring = uIDstring + "&uID[]=" + $(this).val()
			}), jQuery.fn.dialog.open({
				width: 630,
				height: 450,
				modal: !1,
				href: CCM_TOOLS_PATH + "/users/bulk_deactivate?searchInstance=" + a + "&" + uIDstring,
				title: ccmi18n.user_deactivate
			});
			break;
		case "group_add":
			uIDstring = "", $("td.ccm-user-list-cb input[type=checkbox]:checked").each(function() {
				uIDstring = uIDstring + "&uID[]=" + $(this).val()
			}), jQuery.fn.dialog.open({
				width: 630,
				height: 450,
				modal: !1,
				href: CCM_TOOLS_PATH + "/users/bulk_group_add?searchInstance=" + a + "&" + uIDstring,
				title: ccmi18n.user_group_add
			});
			break;
		case "group_remove":
			uIDstring = "", $("td.ccm-user-list-cb input[type=checkbox]:checked").each(function() {
				uIDstring = uIDstring + "&uID[]=" + $(this).val()
			}), jQuery.fn.dialog.open({
				width: 630,
				height: 450,
				modal: !1,
				href: CCM_TOOLS_PATH + "/users/bulk_group_remove?searchInstance=" + a + "&" + uIDstring,
				title: ccmi18n.user_group_remove
			});
			break;
		case "delete":
			uIDstring = "", $("td.ccm-user-list-cb input[type=checkbox]:checked").each(function() {
				uIDstring = uIDstring + "&uID[]=" + $(this).val()
			}), jQuery.fn.dialog.open({
				width: 630,
				height: 450,
				modal: !1,
				href: CCM_TOOLS_PATH + "/users/bulk_delete?searchInstance=" + a + "&" + uIDstring,
				title: ccmi18n.user_delete
			})
		}
		$(this).get(0).selectedIndex = 0
	})
}, ccm_triggerSelectGroup = function(a, b) {
	alert(a), alert(b)
}, ccm_setupGroupSearchPaging = function() {
	$("div#ccm-group-paging").each(function() {
		$(this).closest(".ui-dialog-content").dialog("option", "buttons", [{}]), $(this).closest(".ui-dialog").find(".ui-dialog-buttonpane .ccm-pane-dialog-pagination").remove(), $(this).appendTo($(this).closest(".ui-dialog").find(".ui-dialog-buttonpane").addClass("ccm-ui"))
	})
}, ccm_setupGroupSearch = function(a) {
	$("div.ccm-group a").unbind(), func = a ? window[a] : ccm_triggerSelectGroup, $("div.ccm-group a").each(function() {
		var a = $(this);
		$(this).click(function() {
			return func(a.attr("group-id"), a.attr("group-name")), $.fn.dialog.closeTop(), !1
		})
	}), $("#ccm-group-search").ajaxForm({
		beforeSubmit: function() {
			$("#ccm-group-search-wrapper").html("")
		},
		success: function(a) {
			$("#ccm-group-search-wrapper").html(a)
		}
	}), ccm_setupGroupSearchPaging(), $("div#ccm-group-paging a").click(function() {
		return $("#ccm-group-search-wrapper").html(""), $.ajax({
			type: "GET",
			url: $(this).attr("href"),
			success: function(a) {
				$("#ccm-group-search-wrapper").html(a)
			}
		}), !1
	})
}, ccm_saveArrangement = function(a, b, c, d) {
	a || (a = CCM_CID);
	var e = b.attr("id").substring(1, b.attr("id").indexOf("-")),
		f = c.attr("id").substring(1),
		g = d.attr("id").substring(1);
	ccm_mainNavDisableDirectExit();
	var h = "&sourceBlockID=" + e + "&sourceBlockAreaID=" + f + "&destinationBlockAreaID=" + g,
		j = [c];
	f != g && j.push(d), $.each(j, function(a, b) {
		for (areaStr = "&area[" + b.attr("id").substring(1) + "][]=", bArray = b.sortable("toArray"), i = 0; i < bArray.length; i++) if ("" != bArray[i] && "b" == bArray[i].substring(0, 1)) {
			var c = $("#" + bArray[i]);
			if (c.closest("div.ccm-area")[0] == b[0]) {
				var d = bArray[i].substring(1, bArray[i].indexOf("-"));
				c.attr("custom-style") && (d += "-" + c.attr("custom-style")), h += areaStr + d
			}
		}
	}), $.ajax({
		type: "POST",
		url: CCM_DISPATCHER_FILENAME,
		dataType: "json",
		data: "cID=" + a + "&ccm_token=" + CCM_SECURITY_TOKEN + "&btask=ajax_do_arrange" + h,
		success: function(a) {
			ccm_parseJSON(a, function() {
				$("div.ccm-area").removeClass("ccm-move-mode"), $("div.ccm-block-arrange").each(function() {
					$(this).addClass("ccm-block"), $(this).removeClass("ccm-block-arrange")
				}), ccm_arrangeMode = !1, $(".ccm-main-nav-edit-option").fadeIn(300), ccmAlert.hud(ccmi18n.arrangeBlockMsg, 2e3, "up_down", ccmi18n.arrangeBlock)
			})
		}
	})
}, ccm_arrangeInit = function() {
	ccm_arrangeMode = !0, ccm_hideHighlighter(), $("div.ccm-block").each(function() {
		$(this).addClass("ccm-block-arrange"), $(this).removeClass("ccm-block")
	}), $(".ccm-main-nav-edit-option").fadeOut(300, function() {
		$(".ccm-main-nav-arrange-option").fadeIn(300)
	}), $("div.ccm-area").each(function() {
		var a = $(this),
			b = a.attr("cID");
		a.addClass("ccm-move-mode"), a.sortable({
			items: "div.ccm-block-arrange",
			connectWith: $("div.ccm-area-move-enabled"),
			accept: "div.ccm-block-arrange",
			opacity: .5,
			stop: function(c, d) {
				var e = a,
					f = d.item.closest(".ccm-area");
				ccm_saveArrangement(b, d.item, e, f)
			}
		})
	})
}, "function" != typeof ccm_selectSitemapNode && (ccm_selectSitemapNode = function(a, b) {
	alert(a), alert(b)
}), ccm_goToSitemapNode = function(a) {
	window.location.href = CCM_DISPATCHER_FILENAME + "?cID=" + a
}, ccm_fadeInMenu = function(a, b) {
	var c = a.find("div.popover div.inner").width(),
		d = a.find("div.popover").height();
	a.hide(), a.css("visibility", "visible");
	var e = b.pageX + 2,
		f = b.pageY + 2;
	$(window).height() < b.clientY + d ? (f = f - d - 10, e -= c / 2, a.find("div.popover").removeClass("below"), a.find("div.popover").addClass("above")) : (e -= c / 2, f += 10, a.find("div.popover").removeClass("above"), a.find("div.popover").addClass("below")), a.css("top", f + "px"), a.css("left", e + "px"), a.fadeIn(60)
}, ccm_blockWindowClose = function() {
	jQuery.fn.dialog.closeTop(), ccm_blockWindowAfterClose()
}, ccm_blockWindowAfterClose = function() {
	ccmValidateBlockForm = function() {
		return !0
	}
}, ccm_blockFormSubmit = function() {
	return "function" == typeof window.ccmValidateBlockForm && (r = window.ccmValidateBlockForm(), ccm_isBlockError) ? (jQuery.fn.dialog.hideLoader(), ccm_blockError && ccmAlert.notice(ccmi18n.error, ccm_blockError + "</ul>"), ccm_resetBlockErrors(), !1) : !0
}, ccm_paneToggleOptions = function(a) {
	var b = $(a).parent().find("div.ccm-pane-options-content");
	$(a).hasClass("ccm-icon-option-closed") ? ($(a).removeClass("ccm-icon-option-closed").addClass("ccm-icon-option-open"), b.slideDown("fast", "easeOutExpo")) : ($(a).removeClass("ccm-icon-option-open").addClass("ccm-icon-option-closed"), b.slideUp("fast", "easeOutExpo"))
}, ccm_setupGridStriping = function(a) {
	$("#" + a + " tr").removeClass();
	var b = 0;
	$("#" + a + " tr").each(function() {
		"none" != $(this).css("display") && (b % 2 == 0 && $(this).addClass("ccm-row-alt"), b++)
	})
}, ccm_t = function(a) {
	return $("input[name=ccm-string-" + a + "]").val()
};
var ccmCustomStyle = {
	tabs: function(a, b) {
		return $(".ccm-styleEditPane").hide(), $("#ccm-styleEditPane-" + b).show(), $(a.parentNode.parentNode).find("li").removeClass("ccm-nav-active"), $(a.parentNode).addClass("ccm-nav-active"), !1
	},
	resetAll: function() {
		return confirm(ccmi18n.confirmCssReset) ? (jQuery.fn.dialog.showLoader(), $("#ccm-reset-style").val(1), $("#ccmCustomCssForm").get(0).submit(), !0) : !1
	},
	showPresetDeleteIcon: function() {
		$("select[name=cspID]").val() > 0 ? $("#ccm-style-delete-preset").show() : $("#ccm-style-delete-preset").hide()
	},
	deletePreset: function() {
		var a = $("select[name=cspID]").val();
		if (a > 0) {
			if (!confirm(ccmi18n.confirmCssPresetDelete)) return !1;
			var b = $("#ccm-custom-style-refresh-action").val() + "&deleteCspID=" + a + "&subtask=delete_custom_style_preset";
			jQuery.fn.dialog.showLoader(), $.get(b, function(a) {
				$("#ccm-custom-style-wrapper").html(a), jQuery.fn.dialog.hideLoader()
			})
		}
	},
	initForm: function() {
		$("#cspFooterPreset").length > 0 && $("#ccmCustomCssFormTabs input, #ccmCustomCssFormTabs select, #ccmCustomCssFormTabs textarea").bind("change click", function() {
			$("#cspFooterPreset").show(), $("#cspFooterNoPreset").remove(), $("#ccmCustomCssFormTabs input, #ccmCustomCssFormTabs select").unbind("change click")
		}), $("input[name=cspPresetAction]").click(function() {
			"create_new_preset" == $(this).val() && $(this).prop("checked") ? $("input[name=cspName]").attr("disabled", !1).focus() : $("input[name=cspName]").val("").attr("disabled", !0)
		}), ccmCustomStyle.showPresetDeleteIcon(), ccmCustomStyle.lastPresetID = parseInt($("select[name=cspID]").val()), $("select[name=cspID]").change(function() {
			var a = parseInt($(this).val()),
				b = parseInt($("input[name=selectedCsrID]").val());
			if (ccmCustomStyle.lastPresetID == a) return !1;
			ccmCustomStyle.lastPresetID = a, jQuery.fn.dialog.showLoader();
			var c;
			c = a > 0 ? $("#ccm-custom-style-refresh-action").val() + "&cspID=" + a : $("#ccm-custom-style-refresh-action").val() + "&csrID=" + b, $.get(c, function(a) {
				$("#ccm-custom-style-wrapper").html(a), jQuery.fn.dialog.hideLoader()
			})
		}), $("#ccmCustomCssForm").submit(function() {
			return 1 == $("input[name=cspCreateNew]").prop("checked") && "" == $("input[name=cspName]").val() ? ($("input[name=cspName]").focus(), alert(ccmi18n.errorCustomStylePresetNoName), !1) : (jQuery.fn.dialog.showLoader(), !0)
		}), parseInt(ccmCustomStyle.lastPresetID) || setTimeout(function() {
			$("#ccmCustomCssFormTabs input").attr("disabled", !1).get(0).focus()
		}, 500)
	},
	validIdCheck: function(a, b) {
		var c = $("#" + a.value);
		c && c.get(0) && c.get(0).id != b ? $("#ccm-styles-invalid-id").css("display", "block") : $("#ccm-styles-invalid-id").css("display", "none")
	}
};
$(function() {
	$("#ccm-toolbar").length > 0 && (ccm_intelligentSearchActivateResults(), ccm_intelligentSearchDoRemoteCalls($("#ccm-nav-intelligent-search").val()))
}), ccm_togglePopover = function(a, b) {
	$(".popover").is(":visible") ? $(b).popover("hide") : ($(b).popover("show"), a.stopPropagation(), $(window).bind("click.popover", function() {
		$(b).popover("hide"), $(window).unbind("click.popover")
	}))
}, ccm_toggleQuickNav = function(a, b) {
	var c = $("#ccm-add-to-quick-nav");
	c.hasClass("ccm-icon-favorite-selected") ? c.removeClass("ccm-icon-favorite-selected").addClass("ccm-icon-favorite") : c.removeClass("ccm-icon-favorite").addClass("ccm-icon-favorite-selected");
	var d = $("#ccm-nav-dashboard"),
		e = c.parent().parent().parent().find("h3");
	e.css("display", "inline"), e.effect("transfer", {
		to: d,
		easing: "easeOutExpo"
	}, 600), $.get(CCM_TOOLS_PATH + "/dashboard/add_to_quick_nav", {
		cID: a,
		token: b
	}, function(a) {
		var b = $("<div />").html(a);
		$("#ccm-intelligent-search-results").html(b.find("#ccm-intelligent-search-results").html()), $("#ccm-dashboard-overlay").html(b.find("#ccm-dashboard-overlay").html()), $("#ccm-nav-intelligent-search").data("liveUpdate").setupCache()
	})
};
var ccm_hideToolbarMenusTimer = !1;
ccm_hideToolbarMenus = function() {
	$(".ccm-system-nav-selected").removeClass("ccm-system-nav-selected"), $(".ccm-system-nav-selected").removeClass("ccm-system-nav-selected"), $("#ccm-edit-overlay").fadeOut(90, "easeOutExpo"), $("#ccm-dashboard-overlay").fadeOut(90, "easeOutExpo"), clearTimeout(ccm_hideToolbarMenusTimer)
}, ccm_activateToolbar = function() {
	$("#ccm-dashboard-overlay").css("visibility", "visible").hide(), $("#ccm-nav-intelligent-search-wrapper").click(function() {
		$("#ccm-nav-intelligent-search").focus()
	}), $("#ccm-nav-intelligent-search").focus(function() {
		$(".ccm-system-nav-selected").removeClass("ccm-system-nav-selected"), $(this).parent().addClass("ccm-system-nav-selected"), $("#ccm-dashboard-overlay").is(":visible") && ($("#ccm-dashboard-overlay").fadeOut(90, "easeOutExpo"), $(window).unbind("click.dashboard-nav"))
	}), $(".ccm-nav-edit-mode-active").click(function() {
		return !1
	}), $("#ccm-edit-overlay,#ccm-dashboard-overlay").mouseover(function() {
		clearTimeout(ccm_hideToolbarMenusTimer)
	}), $("#ccm-nav-dashboard").hoverIntent(function() {
		return clearTimeout(ccm_hideToolbarMenusTimer), $(".ccm-system-nav-selected").removeClass("ccm-system-nav-selected"), $(this).parent().addClass("ccm-system-nav-selected"), $("#ccm-nav-intelligent-search").val(""), $("#ccm-intelligent-search-results").fadeOut(90, "easeOutExpo"), $("#ccm-edit-overlay").is(":visible") && ($("#ccm-edit-overlay").fadeOut(90, "easeOutExpo"), $(window).unbind("click.ccm-edit")), $("#ccm-dashboard-overlay").fadeIn(160, "easeOutExpo"), $(window).bind("click.dashboard-nav", function() {
			$(".ccm-system-nav-selected").removeClass("ccm-system-nav-selected"), $("#ccm-dashboard-overlay").fadeOut(90, "easeOutExpo"), $(window).unbind("click.dashboard-nav")
		}), !1
	}, function() {}), $("#ccm-nav-dashboard,#ccm-dashboard-overlay,#ccm-nav-edit,#ccm-edit-overlay").mouseout(function() {
		ccm_hideToolbarMenusTimer = setTimeout(function() {
			ccm_hideToolbarMenus()
		}, 1500)
	}), $("#ccm-nav-intelligent-search").bind("keydown.ccm-intelligent-search", function(a) {
		if (13 == a.keyCode || 40 == a.keyCode || 38 == a.keyCode) {
			if (a.preventDefault(), a.stopPropagation(), 13 == a.keyCode && $("a.ccm-intelligent-search-result-selected").length > 0) {
				var b = $("a.ccm-intelligent-search-result-selected").attr("href");
				b && "#" != b && "javascript:void(0)" != b ? window.location.href = b : $("a.ccm-intelligent-search-result-selected").click()
			}
			var c, d = $("#ccm-intelligent-search-results li:visible");
			(40 == a.keyCode || 38 == a.keyCode) && ($.each(d, function(b, e) {
				$(e).children("a").hasClass("ccm-intelligent-search-result-selected") && (io = 38 == a.keyCode ? d[b - 1] : d[b + 1], c = $(io).find("a"))
			}), c && c.length > 0 && ($("a.ccm-intelligent-search-result-selected").removeClass(), $(c).addClass("ccm-intelligent-search-result-selected")))
		}
	}), $("#ccm-nav-intelligent-search").bind("keyup.ccm-intelligent-search", function() {
		ccm_intelligentSearchDoRemoteCalls($(this).val())
	}), $("#ccm-nav-intelligent-search").blur(function() {
		$(this).parent().removeClass("ccm-system-nav-selected")
	}), $("#ccm-nav-intelligent-search").liveUpdate("ccm-intelligent-search-results", "intelligent-search"), $("#ccm-nav-intelligent-search").bind("click", function() {
		"" == this.value && $("#ccm-intelligent-search-results").hide()
	}), $("#ccm-toolbar-nav-properties").dialog(), $("#ccm-toolbar-nav-preview-as-user").dialog(), $("#ccm-toolbar-add-subpage").dialog(), $("#ccm-toolbar-nav-versions").dialog(), $("#ccm-toolbar-nav-design").dialog(), $("#ccm-toolbar-nav-permissions").dialog(), $("#ccm-toolbar-nav-speed-settings").dialog(), $("#ccm-toolbar-nav-move-copy").dialog(), $("#ccm-toolbar-nav-delete").dialog(), $("#ccm-edit-overlay,#ccm-dashboard-overlay").click(function(a) {
		a.stopPropagation()
	}), $("#ccm-nav-edit").hoverIntent(function() {
		clearTimeout(ccm_hideToolbarMenusTimer), $(".ccm-system-nav-selected").removeClass("ccm-system-nav-selected"), $(this).parent().addClass("ccm-system-nav-selected"), $("#ccm-nav-intelligent-search").val(""), $("#ccm-intelligent-search-results").fadeOut(90, "easeOutExpo"), $("#ccm-dashboard-overlay").is(":visible") && ($("#ccm-dashboard-overlay").fadeOut(90, "easeOutExpo"), $(window).unbind("click.dashboard-nav")), setTimeout("$('#ccm-check-in-comments').focus();", 300), $("#ccm-check-in-preview").click(function() {
			$("#ccm-approve-field").val("PREVIEW"), $("#ccm-check-in").submit()
		}), $("#ccm-check-in-discard").click(function() {
			$("#ccm-approve-field").val("DISCARD"), $("#ccm-check-in").submit()
		}), $("#ccm-check-in-publish").click(function() {
			$("#ccm-approve-field").val("APPROVE"), $("#ccm-check-in").submit()
		});
		var a = $(this).position().left;
		return a > 0 && (a -= 20), $("#ccm-edit-overlay").css("left", a + "px"), $("#ccm-edit-overlay").fadeIn(160, "easeOutExpo", function() {
			$(this).find("a").click(function() {
				ccm_toolbarCloseEditMenu()
			})
		}), $(window).bind("click.ccm-edit", function() {
			ccm_toolbarCloseEditMenu()
		}), !1
	}, function() {})
};
var ajaxtimer = null,
	ajaxquery = null;
ccm_toolbarCloseEditMenu = function() {
	$(".ccm-system-nav-selected").removeClass("ccm-system-nav-selected"), $("#ccm-edit-overlay").fadeOut(90, "easeOutExpo"), $(window).unbind("click.ccm-edit")
}, ccm_intelligentSearchActivateResults = function() {
	0 == $("#ccm-intelligent-search-results div:visible").length && $("#ccm-intelligent-search-results").hide(), $("#ccm-intelligent-search-results a").hover(function() {
		$("a.ccm-intelligent-search-result-selected").removeClass(), $(this).addClass("ccm-intelligent-search-result-selected")
	}, function() {
		$(this).removeClass("ccm-intelligent-search-result-selected")
	})
}, ccm_intelligentSearchDoRemoteCalls = function(a) {
	if (a = jQuery.trim(a), a && a.length > 2) {
		if (a == ajaxquery) return;
		ajaxtimer && window.clearTimeout(ajaxtimer), ajaxquery = a, ajaxtimer = window.setTimeout(function() {
			ajaxtimer = null, $("#ccm-intelligent-search-results-list-marketplace").parent().show(), $("#ccm-intelligent-search-results-list-help").parent().show(), $("#ccm-intelligent-search-results-list-your-site").parent().show(), $("#ccm-intelligent-search-results-list-marketplace").parent().addClass("ccm-intelligent-search-results-module-loading"), $("#ccm-intelligent-search-results-list-help").parent().addClass("ccm-intelligent-search-results-module-loading"), $("#ccm-intelligent-search-results-list-your-site").parent().addClass("ccm-intelligent-search-results-module-loading"), $.getJSON(CCM_TOOLS_PATH + "/marketplace/intelligent_search", {
				q: ajaxquery
			}, function(a) {
				for ($("#ccm-intelligent-search-results-list-marketplace").parent().removeClass("ccm-intelligent-search-results-module-loading"), $("#ccm-intelligent-search-results-list-marketplace").html(""), i = 0; i < a.length; i++) {
					var b = a[i],
						c = "ccm_getMarketplaceItemDetails(" + b.mpID + ")";
					$("#ccm-intelligent-search-results-list-marketplace").append('<li><a href="javascript:void(0)" onclick="' + c + '"><img src="' + b.img + '" />' + b.name + "</a></li>")
				}
				0 == a.length && $("#ccm-intelligent-search-results-list-marketplace").parent().hide(), 0 == $(".ccm-intelligent-search-result-selected").length && ($("#ccm-intelligent-search-results").find("li a").removeClass("ccm-intelligent-search-result-selected"), $("#ccm-intelligent-search-results li:visible a:first").addClass("ccm-intelligent-search-result-selected")), ccm_intelligentSearchActivateResults()
			}).error(function() {
				$("#ccm-intelligent-search-results-list-marketplace").parent().hide()
			}), $.getJSON(CCM_TOOLS_PATH + "/get_remote_help", {
				q: ajaxquery
			}, function(a) {
				for ($("#ccm-intelligent-search-results-list-help").parent().removeClass("ccm-intelligent-search-results-module-loading"), $("#ccm-intelligent-search-results-list-help").html(""), i = 0; i < a.length; i++) {
					var b = a[i];
					$("#ccm-intelligent-search-results-list-help").append('<li><a href="' + b.href + '">' + b.name + "</a></li>")
				}
				0 == a.length && $("#ccm-intelligent-search-results-list-help").parent().hide(), 0 == $(".ccm-intelligent-search-result-selected").length && ($("#ccm-intelligent-search-results").find("li a").removeClass("ccm-intelligent-search-result-selected"), $("#ccm-intelligent-search-results li:visible a:first").addClass("ccm-intelligent-search-result-selected")), ccm_intelligentSearchActivateResults()
			}).error(function() {
				$("#ccm-intelligent-search-results-list-help").parent().hide()
			}), $.getJSON(CCM_TOOLS_PATH + "/pages/intelligent_search", {
				q: ajaxquery
			}, function(a) {
				for ($("#ccm-intelligent-search-results-list-your-site").parent().removeClass("ccm-intelligent-search-results-module-loading"), $("#ccm-intelligent-search-results-list-your-site").html(""), i = 0; i < a.length; i++) {
					var b = a[i];
					$("#ccm-intelligent-search-results-list-your-site").append('<li><a href="' + b.href + '">' + b.name + "</a></li>")
				}
				0 == a.length && $("#ccm-intelligent-search-results-list-your-site").parent().hide(), 0 == $(".ccm-intelligent-search-result-selected").length && ($("#ccm-intelligent-search-results").find("li a").removeClass("ccm-intelligent-search-result-selected"), $("#ccm-intelligent-search-results li:visible a:first").addClass("ccm-intelligent-search-result-selected")), ccm_intelligentSearchActivateResults()
			}).error(function() {
				$("#ccm-intelligent-search-results-list-your-site").parent().hide()
			})
		}, 500)
	}
}, ccm_marketplaceDetailShowMore = function() {
	$(".ccm-marketplace-item-information-more").hide(), $(".ccm-marketplace-item-information-inner").css("max-height", "none")
}, ccm_marketplaceUpdatesShowMore = function(a) {
	$(a).parent().hide(), $(a).parent().parent().find(".ccm-marketplace-update-changelog").css("max-height", "none")
}, ccm_enableDesignScrollers = function() {
	$("a.ccm-scroller-l").hover(function() {
		$(this).find("img").attr("src", CCM_IMAGE_PATH + "/button_scroller_l_active.png")
	}, function() {
		$(this).find("img").attr("src", CCM_IMAGE_PATH + "/button_scroller_l.png")
	}), $("a.ccm-scroller-r").hover(function() {
		$(this).find("img").attr("src", CCM_IMAGE_PATH + "/button_scroller_r_active.png")
	}, function() {
		$(this).find("img").attr("src", CCM_IMAGE_PATH + "/button_scroller_r.png")
	});
	var a = 4,
		b = 132;
	$("a.ccm-scroller-r").unbind("click"), $("a.ccm-scroller-l").unbind("click"), $("a.ccm-scroller-r").click(function() {
		var c = $(this).parent().children("div.ccm-scroller-inner").children("ul"),
			d = $(this).parent().attr("current-page"),
			e = $(this).parent().attr("current-pos"),
			f = $(this).parent().attr("num-pages"),
			g = a * b;
		e = parseInt(e) - g, d++, $(this).parent().attr("current-page", d), $(this).parent().attr("current-pos", e), d == f && $(this).hide(), d > 1 && $(this).siblings("a.ccm-scroller-l").show(), $(c).css("left", e + "px")
	}), $("a.ccm-scroller-l").click(function() {
		var c = $(this).parent().children("div.ccm-scroller-inner").children("ul"),
			d = $(this).parent().attr("current-page"),
			e = $(this).parent().attr("current-pos"),
			f = $(this).parent().attr("num-pages"),
			g = a * b;
		e = parseInt(e) + g, d--, $(this).parent().attr("current-page", d), $(this).parent().attr("current-pos", e), 1 == d && $(this).hide(), f > d && $(this).siblings("a.ccm-scroller-r").show(), $(c).css("left", e + "px")
	}), $("a.ccm-scroller-l").hide(), $("a.ccm-scroller-r").each(function() {
		1 == parseInt($(this).parent().attr("num-pages")) && $(this).hide()
	}), $("#ccm-select-page-type a").click(function() {
		$("#ccm-select-page-type li").each(function() {
			$(this).removeClass("ccm-item-selected")
		}), $(this).parent().addClass("ccm-item-selected"), $("input[name=ctID]").val($(this).attr("ccm-page-type-id"))
	}), $("#ccm-select-theme a").click(function() {
		$("#ccm-select-theme li").each(function() {
			$(this).removeClass("ccm-item-selected")
		}), $(this).parent().addClass("ccm-item-selected"), $("input[name=plID]").val($(this).attr("ccm-theme-id"))
	})
};