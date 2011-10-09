/*<script>*/
// Requires jquery
// Usage:
/* var tabbed = new Tabbed({navClass: '.tabbed-content ul.nav'
							, currentItem: 1
							//, currentClass: 'current'
							//, tabbedClass: 'tabbed'
							//, toggle: true
							//, noDefaultCurrent: false
							//, callback: functionName
							});
*/
function Tabbed(args) {
	// Constructor
	this.construct = function(args) {
		// Required Parameters
		this.navClass = args.navClass;
		// Optional Parameters
		this.toggle = args.toggle == true ? true : false;
		this.noDefaultCurrent = args.noDefaultCurrent == true ? true : false;
		this.currentItem = args.currentItem > 0 ? args.currentItem : (this.toggle || this.noDefaultCurrent ? 0 : 1);
		this.currentClass = args.currentClass !== undefined ? args.currentClass : 'current';
		this.tabbedClass = args.tabbedClass !== undefined ? args.tabbedClass : 'tabbed';
		this.callback = args.callback;
	
		this.currentNav = false;
		this.currentContent = false;
		
		// Use parent <li>s when applicable
		this.navs = $(this.navClass + ' > li');
		// Don't force anchors to be within <li>
		this.navAnchors = $(this.navClass + ' a[href*="#"]:not([href$="#"])');
		this.contents = [];
		
		// Get content containers based on anchor hashes
		var contentIds = [];
		$(this.navAnchors).each(function() {
			if (this.hash) {
				contentIds.push(this.hash);
			}
		});
		this.contents = $(contentIds.join(', '));
		
		// If not toggle, set current to be first <li> that is expanded
		if (!this.toggle) {
			var firstExpand = $(this.navs).filter('.' + this.currentClass).first();
			var firstExpandIndex = $(this.navs).index(firstExpand);
			if (firstExpandIndex >= 0) {
				this.currentItem = firstExpandIndex + 1;
			}
		}
		
		// Return if contents don't exist
		if (this.contents.length < 1) {
			return;
		}
		
		// Append tabbed class to hide
		$([this.navs, this.navAnchors, this.contents]).each((function(i, el) {
			$(el).addClass(this.tabbedClass);
		}).bind(this));
		
		// Set current class to currentItem's nav & content
		$(this.navAnchors).each((function(i, el) {
			if (i + 1 == this.currentItem) {
				this.setCurrents(el);
			}
		}).bind(this));
		
		this.setEvents();
	}
	
	// Set click events on anchors
	this.setEvents = function() {
		var thisClass = this;
		$(this.navAnchors).click(function() {
			this.blur();
				
			// Clear 'current' class from navs and contents
			// Set current nav and content
			// Apply 'current' class
			thisClass.setCurrents(this);
			return false;
		});
	};
	
	// Remove 'current' class name from navs and contents
	this.clearCurrents = function() {
		$([this.navs, this.navAnchors, this.contents]).each((function(i, el) {
			$(el).removeClass(this.currentClass);
		}).bind(this));
	};
	
	// Add 'current' class name on current nav and content
	this.setCurrents = function(targetAnchor) {
		var target = targetAnchor.hash;
		if (!this.toggle) {
			this.clearCurrents();
		}
		
		// Set current on <li> (parent) nav, <a>, and content
		this.currentNav = $(targetAnchor).closest('li');
		this.currentContent = $(target);
		$([this.currentNav, targetAnchor, this.currentContent]).each((function(i, el) {
			if (!this.toggle) {
				$(el).addClass(this.currentClass);
			}
			else {
				$(el).toggleClass(this.currentClass);
			}
		}).bind(this));
		
		if(this.callback != null){
			this.callback.call(this, this.currentNav);
		}
	}
	
	this.construct(args);
}
