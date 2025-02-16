-- Step 1-3: Create Indexes for Optimized Filtering and Sorting
CREATE INDEX idx_orders_status_created_at ON orders (status, created_at DESC);
CREATE INDEX idx_orders_status ON orders (status);
CREATE INDEX idx_orders_created_at ON orders (created_at DESC);
CREATE INDEX idx_orders_covering ON orders (status, created_at DESC, id, column1, column2);

-- Step 4-5: Optimize Query and Check Execution Plan
EXPLAIN ANALYZE 
SELECT id, created_at, column1, column2 
FROM orders 
WHERE status = 'pending' 
ORDER BY created_at DESC 
LIMIT 50;

-- Step 6: Partition the Table for Large Datasets
ALTER TABLE orders 
PARTITION BY LIST COLUMNS(status) (
    PARTITION p_pending VALUES IN ('pending'),
    PARTITION p_completed VALUES IN ('completed')
);
