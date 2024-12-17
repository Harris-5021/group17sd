import pandas as pd
import openpyxl
# Defect report data in table format
data_table = [
    ["Bug ID", "ID Number", "AML-001"],
    ["", "Title", "Wishlist Notification Preferences Validation Error"],
    ["", "Reporter", "Quality Team"],
    ["", "Submit date", "12/17/24"],
    ["Bug overview", "Summary", "When setting notification preferences for wishlist items, the system accepts invalid values without proper validation"],
    ["", "URL", "/wishlist/notification-preferences"],
    ["Environment", "Platform", "Web Application"],
    ["", "Operating System", "Cross-platform"],
    ["", "Browser", "All major browsers"],
    ["Bug details", "Steps to reproduce", "1. Log in as library member<br>2. Add item to wishlist<br>3. Attempt to set invalid notification preference<br>4. Submit changes"],
    ["", "Expected result", "System should validate and only accept valid preference values (enabled/disabled)"],
    ["", "Actual result", "System accepts any value without validation"],
    ["", "Severity", "Major"],
    ["Bug tracking", "Assigned to", "Development Team"],
    ["", "Priority", "High"],
    ["Notes", "Notes", "Impacts user experience and system reliability"],
    
    ["Bug ID", "ID Number", "AML-002"],
    ["", "Title", "Media Return Process Race Condition"],
    ["", "Reporter", "Security Team"],
    ["", "Submit date", "12/17/24"],
    ["Bug overview", "Summary", "Multiple simultaneous returns of the same media item can lead to incorrect inventory counts"],
    ["", "URL", "/media/return"],
    ["Environment", "Platform", "Web Application"],
    ["", "Operating System", "Cross-platform"],
    ["", "Browser", "All major browsers"],
    ["Bug details", "Steps to reproduce", "1. Have two users access return process for same media<br>2. Both initiate return simultaneously<br>3. Observe inventory count"],
    ["", "Expected result", "System should handle concurrent returns correctly"],
    ["", "Actual result", "Race condition causes incorrect inventory count"],
    ["", "Severity", "Major"],
    ["Bug tracking", "Assigned to", "Development Team"],
    ["", "Priority", "High"],
    ["Notes", "Notes", "Critical data consistency issue requiring immediate attention"],
    
    ["Bug ID", "ID Number", "AML-003"],
    ["", "Title", "WCAG 2.0 Compliance Failure in Search Results"],
    ["", "Reporter", "Accessibility Team"],
    ["", "Submit date", "12/17/24"],
    ["Bug overview", "Summary", "Search results page fails to meet WCAG 2.0 accessibility standards"],
    ["", "URL", "/media/search"],
    ["Environment", "Platform", "Web Application"],
    ["", "Operating System", "Cross-platform"],
    ["", "Browser", "All major browsers"],
    ["Bug details", "Steps to reproduce", "1. Navigate to search page<br>2. Use screen reader<br>3. Attempt to interact with search results"],
    ["", "Expected result", "All elements should be screen reader accessible"],
    ["", "Actual result", "Missing ARIA labels and keyboard navigation"],
    ["", "Severity", "Major"],
    ["Bug tracking", "Assigned to", "UI Team"],
    ["", "Priority", "High"],
    ["Notes", "Notes", "Violates accessibility requirements"],
    
    ["Bug ID", "ID Number", "AML-004"],
    ["", "Title", "Procurement Cost Negative Value Acceptance"],
    ["", "Reporter", "Testing Team"],
    ["", "Submit date", "12/17/24"],
    ["Bug overview", "Summary", "System accepts negative values for procurement costs"],
    ["", "URL", "/procurement/new"],
    ["Environment", "Platform", "Web Application"],
    ["", "Operating System", "Cross-platform"],
    ["", "Browser", "All major browsers"],
    ["Bug details", "Steps to reproduce", "1. Login as Purchase Manager<br>2. Create new procurement<br>3. Enter negative cost value<br>4. Submit form"],
    ["", "Expected result", "System should reject negative values"],
    ["", "Actual result", "Negative values are accepted"],
    ["", "Severity", "Minor"],
    ["Bug tracking", "Assigned to", "Development Team"],
    ["", "Priority", "Medium"],
    ["Notes", "Notes", "Data validation issue requiring fix"],
    
    ["Bug ID", "ID Number", "AML-005"],
    ["", "Title", "Missing Subscription Change Audit Trail"],
    ["", "Reporter", "Security Team"],
    ["", "Submit date", "12/17/24"],
    ["Bug overview", "Summary", "No audit logging for subscription amount changes"],
    ["", "URL", "/subscriptions/manage"],
    ["Environment", "Platform", "Web Application"],
    ["", "Operating System", "Cross-platform"],
    ["", "Browser", "All major browsers"],
    ["Bug details", "Steps to reproduce", "1. Login as Accountant<br>2. Change subscription amount<br>3. Check system logs"],
    ["", "Expected result", "All changes should be logged with user details"],
    ["", "Actual result", "No audit trail exists for changes"],
    ["", "Severity", "Major"],
    ["Bug tracking", "Assigned to", "Development Team"],
    ["", "Priority", "High"],
    ["Notes", "Notes", "Security and compliance concern"]
]

# Converting to DataFrame
df_table = pd.DataFrame(data_table, columns=["Category", "Label", "Value"])

# Save as Excel
file_path_table = "/mnt/data/AML_Defect_Table_Report.xlsx"
df_table.to_excel(file_path_table, index=False)

file_path_table
